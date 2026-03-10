<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'clientes';

    protected $fillable = [
        'empresa_id',
        'vendedor_id',
        'tipo_solicitud',
        'fecha_diligenciamiento',
        'zona',
        'tipo_documento',
        'numero_documento',
        'nombre_razon_social',
        'nombre_establecimiento',
        'correo_electronico',
        'direccion',
        'barrio',
        'ciudad_departamento',
        'celular',
        'tipo_negocio',
        'tipo_negocio_otro',
        'representante_legal_nombre',
        'representante_legal_tipo_documento',
        'representante_legal_numero_documento',
        'codigo_ciiu',
        'actividad_economica',
        'agente_retencion_fuente',
        'responsable_iva',
        'tipo_regimen',
        'agente_retencion_ico',
        'correo_factura_electronica',
        'fuente_recursos',
        'firma_base64',
        'foto_url',
        'cedula_pdf_url',
        'rut_pdf_url',
        'persona_natural_no_responsable_iva',
        'cliente_contado',
        'checklist_formato',
        'checklist_documento_identidad',
        'checklist_rut',
        'ip_dispositivo',
        'user_agent_dispositivo',
        'codigo_verificacion_qr',
        'pdf_url',
        'latitud',
        'longitud',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_diligenciamiento' => 'date',
            'agente_retencion_fuente' => 'boolean',
            'agente_retencion_ico' => 'boolean',
            'cliente_contado' => 'boolean',
            'persona_natural_no_responsable_iva' => 'boolean',
            'checklist_formato' => 'boolean',
            'checklist_documento_identidad' => 'boolean',
            'checklist_rut' => 'boolean',
            'latitud' => 'decimal:7',
            'longitud' => 'decimal:7',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new EmpresaScope());
        static::creating(function (Cliente $cliente): void {
            if (empty($cliente->codigo_verificacion_qr)) {
                $cliente->codigo_verificacion_qr = Str::uuid()->toString();
            }
        });
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function scopeCompletados($query)
    {
        return $query->where('estado', 'completado');
    }

    public function scopeBorradores($query)
    {
        return $query->where('estado', 'borrador');
    }

    public function scopeDeEmpresa($query, int $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    public function scopeDeVendedor($query, int $vendedorId)
    {
        return $query->where('vendedor_id', $vendedorId);
    }

    public function estaCompletado(): bool
    {
        return $this->estado === 'completado';
    }

    public function esPersonaJuridica(): bool
    {
        return $this->tipo_documento === 'NIT';
    }

    public function tipoNegocioTexto(): string
    {
        if ($this->tipo_negocio === 'otro') {
            return $this->tipo_negocio_otro ?? 'Otro';
        }

        return ucfirst($this->tipo_negocio);
    }
}
