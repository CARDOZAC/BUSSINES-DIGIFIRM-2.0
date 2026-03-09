<?php

namespace App\Exports;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientesExport implements FromQuery, WithHeadings, WithMapping
{
    private array $filtros;

    public function __construct(array $filtros)
    {
        $this->filtros = $filtros;
    }

    public function query()
    {
        return Cliente::with(['empresa', 'vendedor'])
            ->completados()
            ->when($this->filtros['busqueda'] ?? null, function ($q, $busqueda) {
                $term = '%' . $busqueda . '%';
                $q->where(function ($sub) use ($term) {
                    $sub->where('nombre_razon_social', 'like', $term)
                        ->orWhere('numero_documento', 'like', $term)
                        ->orWhere('nombre_establecimiento', 'like', $term);
                });
            })
            ->when($this->filtros['empresa_id'] ?? null, fn ($q, $v) => $q->where('empresa_id', $v))
            ->when($this->filtros['vendedor_id'] ?? null, fn ($q, $v) => $q->where('vendedor_id', $v))
            ->when($this->filtros['fecha_desde'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '>=', $v))
            ->when($this->filtros['fecha_hasta'] ?? null, fn ($q, $v) => $q->whereDate('created_at', '<=', $v))
            ->when($this->filtros['tipo_negocio'] ?? null, fn ($q, $v) => $q->where('tipo_negocio', $v))
            ->latest();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Empresa',
            'Nombre / Razón Social',
            'Tipo Doc.',
            'Nro. Documento',
            'Establecimiento',
            'Ciudad',
            'Celular',
            'Tipo Negocio',
            'Vendedor',
            'Fecha Creación',
            'URL PDF',
        ];
    }

    public function map($cliente): array
    {
        return [
            $cliente->id,
            $cliente->empresa->nombre ?? 'N/A',
            $cliente->nombre_razon_social,
            $cliente->tipo_documento,
            $cliente->numero_documento,
            $cliente->nombre_establecimiento ?? '',
            $cliente->ciudad_departamento,
            $cliente->celular,
            $cliente->tipoNegocioTexto(),
            $cliente->vendedor->name ?? 'N/A',
            $cliente->created_at->format('d/m/Y H:i'),
            $cliente->pdf_url ?? '',
        ];
    }
}
