<?php

namespace App\Livewire;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Services\ActivityLogService;
use App\Services\CloudinaryService;
use App\Services\PdfClienteService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClienteWizard extends Component
{
    use WithFileUploads;

    public int $paso = 1;
    public int $totalPasos = 4;

    // --- Paso 1: Empresa y Solicitud ---
    public ?int $empresa_id = null;
    public string $tipo_solicitud = '';
    public string $fecha_diligenciamiento = '';
    public string $zona = '';

    // --- Paso 2: Información General ---
    public string $tipo_documento = '';
    public string $numero_documento = '';
    public string $nombre_razon_social = '';
    public string $nombre_establecimiento = '';
    public string $correo_electronico = '';
    public string $direccion = '';
    public string $barrio = '';
    public string $ciudad_departamento = '';
    public string $celular = '';
    public string $tipo_negocio = '';
    public string $tipo_negocio_otro = '';
    public string $representante_legal_nombre = '';
    public string $representante_legal_tipo_documento = '';
    public string $representante_legal_numero_documento = '';

    // --- Paso 3: Información Tributaria ---
    public string $codigo_ciiu = '';
    public string $actividad_economica = '';
    public bool $agente_retencion_fuente = false;
    public string $responsable_iva = 'no_responsable';
    public ?string $tipo_regimen = null;
    public bool $agente_retencion_ico = false;
    public string $correo_factura_electronica = '';
    public string $fuente_recursos = '';

    // --- Paso 4: Firma, Foto y Confirmación ---
    public string $firma_base64 = '';
    public $foto_cliente = null; // TemporaryUploadedFile
    public ?string $foto_preview_url = null;
    public bool $cliente_contado = true;
    public string $observaciones = '';
    public bool $confirmacion_datos = false;

    // --- Estado UI ---
    public ?Empresa $empresaSeleccionada = null;
    public bool $guardando = false;
    public bool $guardadoExitoso = false;
    public ?int $clienteGuardadoId = null;

    public function mount(): void
    {
        $this->fecha_diligenciamiento = now()->format('Y-m-d');

        $usuario = Auth::user();
        if ($usuario && $usuario->empresa_id) {
            $this->empresa_id = $usuario->empresa_id;
            $this->empresaSeleccionada = Empresa::find($usuario->empresa_id);
        }
    }

    public function updatedEmpresaId(?int $valor): void
    {
        $this->empresaSeleccionada = $valor ? Empresa::find($valor) : null;
    }

    public function updatedFotoCliente(): void
    {
        $this->validate([
            'foto_cliente' => 'image|max:5120',
        ], [
            'foto_cliente.image' => 'El archivo debe ser una imagen.',
            'foto_cliente.max' => 'La foto no debe superar 5MB.',
        ]);

        if ($this->foto_cliente) {
            $this->foto_preview_url = $this->foto_cliente->temporaryUrl();
        }
    }

    public function getProgresoPorcentajeProperty(): int
    {
        return intval(($this->paso / $this->totalPasos) * 100);
    }

    public function siguientePaso(): void
    {
        $this->validate($this->reglasDelPaso($this->paso), $this->mensajesValidacion());
        $this->paso = min($this->paso + 1, $this->totalPasos);
    }

    public function pasoAnterior(): void
    {
        $this->paso = max($this->paso - 1, 1);
    }

    public function irAPaso(int $paso): void
    {
        if ($paso < $this->paso) {
            $this->paso = $paso;
        }
    }

    public function limpiarFirma(): void
    {
        $this->firma_base64 = '';
        $this->dispatch('firma-limpiada');
    }

    public function guardarCliente(): void
    {
        $this->validate($this->reglasDelPaso(4), $this->mensajesValidacion());

        if (!$this->confirmacion_datos) {
            $this->addError('confirmacion_datos', 'Debe confirmar que los datos son verídicos.');
            return;
        }

        $this->guardando = true;

        try {
            $fotoUrl = null;
            if ($this->foto_cliente) {
                $cloudinary = app(CloudinaryService::class);
                $resultado = $cloudinary->subirFotoCliente($this->foto_cliente);
                $fotoUrl = $resultado['url'];
            }

            $cliente = Cliente::create([
                'empresa_id' => $this->empresa_id,
                'vendedor_id' => Auth::id(),
                'tipo_solicitud' => $this->tipo_solicitud,
                'fecha_diligenciamiento' => $this->fecha_diligenciamiento,
                'zona' => $this->zona,
                'tipo_documento' => $this->tipo_documento,
                'numero_documento' => $this->numero_documento,
                'nombre_razon_social' => $this->nombre_razon_social,
                'nombre_establecimiento' => $this->nombre_establecimiento,
                'correo_electronico' => $this->correo_electronico ?: null,
                'direccion' => $this->direccion,
                'barrio' => $this->barrio,
                'ciudad_departamento' => $this->ciudad_departamento,
                'celular' => $this->celular,
                'tipo_negocio' => $this->tipo_negocio,
                'tipo_negocio_otro' => $this->tipo_negocio === 'otro' ? $this->tipo_negocio_otro : null,
                'representante_legal_nombre' => $this->representante_legal_nombre ?: null,
                'representante_legal_tipo_documento' => $this->representante_legal_tipo_documento ?: null,
                'representante_legal_numero_documento' => $this->representante_legal_numero_documento ?: null,
                'codigo_ciiu' => $this->codigo_ciiu ?: null,
                'actividad_economica' => $this->actividad_economica ?: null,
                'agente_retencion_fuente' => $this->agente_retencion_fuente,
                'responsable_iva' => $this->responsable_iva,
                'tipo_regimen' => $this->tipo_regimen,
                'agente_retencion_ico' => $this->agente_retencion_ico,
                'correo_factura_electronica' => $this->correo_factura_electronica ?: null,
                'fuente_recursos' => $this->fuente_recursos ?: null,
                'firma_base64' => $this->firma_base64,
                'foto_url' => $fotoUrl,
                'cliente_contado' => $this->cliente_contado,
                'ip_dispositivo' => request()->ip(),
                'user_agent_dispositivo' => request()->userAgent(),
                'estado' => 'completado',
            ]);

            app(ActivityLogService::class)->registrarCreacion(
                $cliente,
                "Cliente {$cliente->nombre_razon_social} creado por " . Auth::user()->name
            );

            try {
                app(PdfClienteService::class)->generarYSubir($cliente);
            } catch (\Throwable $e) {
                logger()->error("Error generando PDF para cliente #{$cliente->id}: " . $e->getMessage());
            }

            $this->clienteGuardadoId = $cliente->id;
            $this->guardadoExitoso = true;
            $this->dispatch('cliente-guardado', clienteId: $cliente->id);

        } catch (\Throwable $e) {
            logger()->error("Error guardando cliente: " . $e->getMessage());
            session()->flash('error', 'Error al guardar el cliente. Intente nuevamente.');
        } finally {
            $this->guardando = false;
        }
    }

    public function nuevoCliente(): void
    {
        $this->reset();
        $this->mount();
    }

    private function reglasDelPaso(int $paso): array
    {
        return match ($paso) {
            1 => [
                'empresa_id' => 'required|exists:empresas,id',
                'tipo_solicitud' => 'required|in:creacion,actualizacion,reactivacion',
                'fecha_diligenciamiento' => 'required|date',
                'zona' => 'nullable|string|max:100',
            ],
            2 => [
                'tipo_documento' => 'required|in:CC,NIT,CE',
                'numero_documento' => 'required|string|min:5|max:30',
                'nombre_razon_social' => 'required|string|min:3|max:250',
                'nombre_establecimiento' => 'nullable|string|max:250',
                'correo_electronico' => 'nullable|email|max:200',
                'direccion' => 'required|string|min:5|max:300',
                'barrio' => 'nullable|string|max:150',
                'ciudad_departamento' => 'required|string|min:3|max:150',
                'celular' => 'required|string|min:7|max:20',
                'tipo_negocio' => 'required|in:mayorista,supermercado,tienda,otro',
                'tipo_negocio_otro' => 'required_if:tipo_negocio,otro|nullable|string|max:100',
                'representante_legal_nombre' => 'nullable|string|max:250',
                'representante_legal_tipo_documento' => 'nullable|in:CC,NIT,CE',
                'representante_legal_numero_documento' => 'nullable|string|max:30',
            ],
            3 => [
                'codigo_ciiu' => 'nullable|string|max:10',
                'actividad_economica' => 'nullable|string|max:200',
                'agente_retencion_fuente' => 'boolean',
                'responsable_iva' => 'required|in:responsable,no_responsable',
                'tipo_regimen' => 'nullable|in:gran_contribuyente,autorretenedor',
                'agente_retencion_ico' => 'boolean',
                'correo_factura_electronica' => 'nullable|email|max:200',
                'fuente_recursos' => 'nullable|string|max:1000',
            ],
            4 => [
                'firma_base64' => 'required|string|min:50',
                'foto_cliente' => 'nullable|image|max:5120',
                'cliente_contado' => 'boolean',
                'confirmacion_datos' => 'accepted',
            ],
            default => [],
        };
    }

    private function mensajesValidacion(): array
    {
        return [
            'empresa_id.required' => 'Seleccione la empresa.',
            'empresa_id.exists' => 'La empresa seleccionada no es válida.',
            'tipo_solicitud.required' => 'Seleccione el tipo de solicitud.',
            'tipo_solicitud.in' => 'El tipo de solicitud no es válido.',
            'fecha_diligenciamiento.required' => 'La fecha es obligatoria.',
            'tipo_documento.required' => 'Seleccione el tipo de documento.',
            'tipo_documento.in' => 'Tipo de documento no válido.',
            'numero_documento.required' => 'El número de documento es obligatorio.',
            'numero_documento.min' => 'El documento debe tener mínimo 5 caracteres.',
            'nombre_razon_social.required' => 'El nombre o razón social es obligatorio.',
            'nombre_razon_social.min' => 'El nombre debe tener mínimo 3 caracteres.',
            'correo_electronico.email' => 'Ingrese un correo electrónico válido.',
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.min' => 'La dirección debe tener mínimo 5 caracteres.',
            'ciudad_departamento.required' => 'La ciudad/departamento es obligatorio.',
            'celular.required' => 'El número de celular es obligatorio.',
            'celular.min' => 'El celular debe tener mínimo 7 dígitos.',
            'tipo_negocio.required' => 'Seleccione el tipo de negocio.',
            'tipo_negocio_otro.required_if' => 'Especifique el tipo de negocio.',
            'responsable_iva.required' => 'Seleccione la responsabilidad de IVA.',
            'correo_factura_electronica.email' => 'Ingrese un correo válido para factura electrónica.',
            'firma_base64.required' => 'La firma del cliente es obligatoria.',
            'firma_base64.min' => 'La firma capturada no es válida. Firme nuevamente.',
            'foto_cliente.image' => 'El archivo debe ser una imagen.',
            'foto_cliente.max' => 'La foto no debe superar 5MB.',
            'confirmacion_datos.accepted' => 'Debe confirmar que los datos son verídicos y la firma es del cliente.',
        ];
    }

    public function render()
    {
        return view('livewire.cliente-wizard', [
            'empresas' => Empresa::activas()->orderBy('nombre')->get(),
            'progresoPorcentaje' => $this->progresoPorcentaje,
        ])->layout('layouts.app');
    }
}
