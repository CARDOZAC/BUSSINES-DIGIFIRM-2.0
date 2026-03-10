<div>
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-5">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Módulo de Auditoría</h1>
            <p class="text-sm text-gray-600 mt-0.5">Registro de actividad del sistema</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-4">
        {{-- Filtros --}}
        <div class="card bg-base-100 shadow">
            <div class="card-body p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                    <div>
                        <label class="block text-xs font-semibold text-base-content/60 mb-1">Vendedor</label>
                        <select wire:model.live="user_id" class="select select-sm w-full">
                            <option value="">Todos</option>
                            @foreach($vendedores as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-base-content/60 mb-1">Empresa</label>
                        <select wire:model.live="empresa_id" class="select select-sm w-full">
                            <option value="">Todas</option>
                            @foreach($empresas as $e)
                                <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-base-content/60 mb-1">Acción</label>
                        <select wire:model.live="accion" class="select select-sm w-full">
                            <option value="">Todas</option>
                            @foreach($acciones as $a)
                                <option value="{{ $a }}">{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-base-content/60 mb-1">Desde</label>
                        <input type="date" wire:model.live="fecha_desde" class="input input-sm w-full">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-base-content/60 mb-1">Hasta</label>
                        <input type="date" wire:model.live="fecha_hasta" class="input input-sm w-full">
                    </div>
                </div>
                @if($user_id || $empresa_id || $accion || $fecha_desde || $fecha_hasta)
                <div class="mt-3">
                    <button wire:click="limpiarFiltros" class="btn btn-ghost btn-sm">Limpiar filtros</button>
                </div>
                @endif
            </div>
        </div>

        {{-- Tabla --}}
        <div class="card bg-base-100 shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th class="text-xs">Vendedor</th>
                            <th class="text-xs hidden md:table-cell">Empresa</th>
                            <th class="text-xs">Acción</th>
                            <th class="text-xs hidden lg:table-cell">Módulo</th>
                            <th class="text-xs hidden md:table-cell">IP</th>
                            <th class="text-xs hidden lg:table-cell">Ubicación</th>
                            <th class="text-xs">Fecha/Hora</th>
                            <th class="text-xs hidden xl:table-cell">Dispositivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td class="text-sm">
                                {{ $log->usuario?->name ?? '—' }}
                            </td>
                            <td class="text-sm hidden md:table-cell">
                                {{ $log->empresa?->nombre ?? '—' }}
                            </td>
                            <td>
                                <span class="badge badge-sm badge-outline">{{ $log->accion }}</span>
                            </td>
                            <td class="text-sm hidden lg:table-cell">{{ $log->modulo ?? '—' }}</td>
                            <td class="text-xs font-mono hidden md:table-cell">{{ $log->ip ?? '—' }}</td>
                            <td class="text-xs hidden lg:table-cell max-w-[120px] truncate" title="{{ $log->ubicacionTexto() }}">
                                {{ $log->ubicacionTexto() }}
                            </td>
                            <td class="text-xs whitespace-nowrap">
                                {{ $log->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="text-xs hidden xl:table-cell max-w-[180px] truncate" title="{{ $log->user_agent }}">
                                {{ Str::limit($log->user_agent, 40) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-8 text-base-content/60">
                                No hay registros de auditoría.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-base-200">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
