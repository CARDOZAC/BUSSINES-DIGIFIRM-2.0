<?php

namespace App\Exports;

use App\Models\Cliente;
use App\Models\Proveedor;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ClientesExport implements FromQuery, WithHeadings, WithMapping
{
    private array $filtros;
    private $proveedores;

    public function __construct(array $filtros)
    {
        $this->filtros = $filtros;
        $this->proveedores = Proveedor::orderBy('nombre')->get();
    }

    public function query()
    {
        return Cliente::with(['empresa', 'vendedor', 'proveedoresMapeados'])
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
        $headings = [
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
        ];

        foreach ($this->proveedores as $proveedor) {
            $headings[] = 'Código ' . $proveedor->nombre;
        }

        $headings[] = 'URL PDF';

        return $headings;
    }

    public function map($cliente): array
    {
        $data = [
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
        ];

        $mapeos = $cliente->proveedoresMapeados->pluck('pivot.code', 'id');

        foreach ($this->proveedores as $proveedor) {
            $data[] = $mapeos[$proveedor->id] ?? '';
        }

        $data[] = $cliente->pdf_url ?? '';

        return $data;
    }
}
