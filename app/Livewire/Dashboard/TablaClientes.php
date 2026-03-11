<?php

namespace App\Livewire\Dashboard;

use App\Exports\ClientesExport;
use App\Models\Cliente;
use App\Services\ActivityLogService;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TablaClientes extends Component
{
    use WithPagination;

    public string $busqueda = '';
    public ?int $empresa_id = null;
    public ?int $vendedor_id = null;
    public ?string $fecha_desde = null;
    public ?string $fecha_hasta = null;
    public ?string $tipo_negocio = null;
    public int $perPage = 15;

    // Mapping providers
    public ?int $selectedClientId = null;
    public ?int $selectedProviderId = null;
    public string $providerCode = '';

    protected $queryString = [
        'busqueda' => ['except' => ''],
        'empresa_id' => ['except' => null],
        'vendedor_id' => ['except' => null],
        'fecha_desde' => ['except' => null],
        'fecha_hasta' => ['except' => null],
        'tipo_negocio' => ['except' => null],
    ];

    public function updatedBusqueda(): void
    {
        $this->resetPage();
    }

    public function updatedEmpresaId(): void
    {
        $this->resetPage();
    }

    public function updatedVendedorId(): void
    {
        $this->resetPage();
    }

    public function updatedFechaDesde(): void
    {
        $this->resetPage();
    }

    public function updatedFechaHasta(): void
    {
        $this->resetPage();
    }

    public function updatedTipoNegocio(): void
    {
        $this->resetPage();
    }

    public function selectClienteParaMapeo(int $clienteId): void
    {
        $this->selectedClientId = $clienteId;
        $this->selectedProviderId = null;
        $this->providerCode = '';
        $this->dispatch('open-mapeo-modal');
    }

    public function guardarMapeo(): void
    {
        $this->validate([
            'selectedClientId' => 'required|exists:clientes,id',
            'selectedProviderId' => 'required|exists:proveedores,id',
            'providerCode' => 'required|string|max:255',
        ]);

        $cliente = Cliente::findOrFail($this->selectedClientId);
        $cliente->proveedoresMapeados()->syncWithoutDetaching([
            $this->selectedProviderId => ['code' => $this->providerCode]
        ]);

        $this->selectedClientId = null;
        $this->selectedProviderId = null;
        $this->providerCode = '';

        $this->dispatch('close-mapeo-modal');
        session()->flash('message', 'Código de proveedor asignado correctamente.');
    }

    public function limpiarFiltros(): void
    {
        $this->reset(['busqueda', 'empresa_id', 'vendedor_id', 'fecha_desde', 'fecha_hasta', 'tipo_negocio']);
        $this->resetPage();
    }

    public function archivarCliente(int $clienteId): void
    {
        $cliente = Cliente::findOrFail($clienteId);
        $this->authorize('delete', $cliente);
        $cliente->update(['estado' => 'anulado']);
        app(ActivityLogService::class)->registrarEliminacion($cliente, "Cliente #{$clienteId} archivado");
        $cliente->delete();

        session()->flash('message', "Cliente #{$clienteId} archivado correctamente.");
    }

    public function exportarCsv(): BinaryFileResponse
    {
        $this->authorize('export', \App\Models\Cliente::class);

        $usuario = Auth::user();

        $filtros = [
            'busqueda' => $this->busqueda,
            'empresa_id' => $this->empresa_id,
            'vendedor_id' => $this->vendedor_id,
            'fecha_desde' => $this->fecha_desde,
            'fecha_hasta' => $this->fecha_hasta,
            'tipo_negocio' => $this->tipo_negocio,
        ];

        if ($usuario->esVendedor() && !$usuario->esAdminCartera()) {
            $filtros['vendedor_id'] = $usuario->id;
        }

        $nombre = 'clientes_' . now()->format('Y-m-d_His') . '.csv';

        app(ActivityLogService::class)->registrarAccion('exportar', 'clientes', null, 'Exportación CSV de clientes');

        return Excel::download(new ClientesExport($filtros), $nombre, \Maatwebsite\Excel\Excel::CSV);
    }

    private function consultaFiltrada()
    {
        return Cliente::with(['empresa', 'vendedor'])
            ->completados()
            ->when($this->busqueda, function ($q) {
                $term = '%' . $this->busqueda . '%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('nombre_razon_social', 'like', $term)
                        ->orWhere('numero_documento', 'like', $term)
                        ->orWhere('nombre_establecimiento', 'like', $term);
                });
            })
            ->when($this->empresa_id, fn ($q) => $q->where('empresa_id', $this->empresa_id))
            ->when($this->vendedor_id, fn ($q) => $q->where('vendedor_id', $this->vendedor_id))
            ->when($this->fecha_desde, fn ($q) => $q->whereDate('created_at', '>=', $this->fecha_desde))
            ->when($this->fecha_hasta, fn ($q) => $q->whereDate('created_at', '<=', $this->fecha_hasta))
            ->when($this->tipo_negocio, fn ($q) => $q->where('tipo_negocio', $this->tipo_negocio))
            ->latest();
    }

    public function render()
    {
        $usuario = Auth::user();

        $query = $this->consultaFiltrada();

        if ($usuario->esVendedor() && !$usuario->esAdminCartera()) {
            $query->where('vendedor_id', $usuario->id);
        }

        $totalClientes = (clone $query)->count();

        $empresas = $usuario->esAdminCartera() || $usuario->hasRole('super_admin')
            ? Empresa::activas()->orderBy('nombre')->get()
            : Empresa::where('id', $usuario->empresa_id)->get();
        $vendedores = $usuario->esAdminCartera() || $usuario->hasRole('super_admin')
            ? User::activos()->orderBy('name')->get()
            : User::activos()->where('empresa_id', $usuario->empresa_id)->orderBy('name')->get();

        $proveedores = $this->selectedClientId
            ? \App\Models\Proveedor::where('empresa_id', Cliente::find($this->selectedClientId)?->empresa_id)->get()
            : collect();

        return view('livewire.dashboard.tabla-clientes', [
            'clientes' => $query->paginate($this->perPage),
            'empresas' => $empresas,
            'vendedores' => $vendedores,
            'proveedores' => $proveedores,
            'totalClientes' => $totalClientes,
            'esAdmin' => $usuario->esAdminCartera(),
        ])->layout('layouts.app');
    }
}
