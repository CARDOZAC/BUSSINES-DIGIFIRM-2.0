<?php

namespace App\Livewire\Admin;

use App\Models\ActivityLog;
use App\Models\Empresa;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AuditoriaIndex extends Component
{
    use WithPagination;

    public ?int $user_id = null;
    public ?int $empresa_id = null;
    public ?string $accion = null;
    public ?string $fecha_desde = null;
    public ?string $fecha_hasta = null;

    protected $queryString = [
        'user_id' => ['except' => null],
        'empresa_id' => ['except' => null],
        'accion' => ['except' => null],
        'fecha_desde' => ['except' => null],
        'fecha_hasta' => ['except' => null],
    ];

    public function limpiarFiltros(): void
    {
        $this->reset(['user_id', 'empresa_id', 'accion', 'fecha_desde', 'fecha_hasta']);
        $this->resetPage();
    }

    public function render()
    {
        $query = ActivityLog::with(['usuario', 'empresa'])
            ->when($this->user_id, fn ($q) => $q->where('user_id', $this->user_id))
            ->when($this->empresa_id, fn ($q) => $q->where('empresa_id', $this->empresa_id))
            ->when($this->accion, fn ($q) => $q->where('accion', $this->accion))
            ->when($this->fecha_desde, fn ($q) => $q->whereDate('created_at', '>=', $this->fecha_desde))
            ->when($this->fecha_hasta, fn ($q) => $q->whereDate('created_at', '<=', $this->fecha_hasta))
            ->latest();

        $acciones = ActivityLog::select('accion')->distinct()->pluck('accion')->sort()->values();

        return view('livewire.admin.auditoria-index', [
            'logs' => $query->paginate(25),
            'vendedores' => User::with('empresa')->orderBy('name')->get(),
            'empresas' => Empresa::activas()->orderBy('nombre')->get(),
            'acciones' => $acciones,
        ])->layout('layouts.app');
    }
}
