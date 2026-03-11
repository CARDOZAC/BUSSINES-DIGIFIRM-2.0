<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Proveedores</h1>
                <p class="text-sm text-gray-600 mt-0.5">
                    <span class="font-semibold">{{ $proveedores->total() }}</span> proveedores encontrados
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('proveedores.create') }}" class="btn btn-sm btn-primary gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    Nuevo Proveedor
                </a>
                <a href="{{ route('proveedores.export', request()->query()) }}" class="btn btn-sm btn-success gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Exportar CSV
                </a>
                <button onclick="document.getElementById('modal-importar').showModal()" class="btn btn-sm btn-outline btn-info gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4 4m0 0L8 8m4-4v12" /></svg>
                    Importar CSV
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
            @if(session()->has('message'))
                <div class="alert alert-success shadow-md text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('message') }}</span>
                </div>
            @endif

            {{-- Filtros --}}
            <div class="card bg-base-100 shadow">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('proveedores.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 xl:grid-cols-7 gap-3 items-end">
                        @if($empresas->isNotEmpty())
                        <div>
                            <label class="block text-xs font-semibold text-base-content/60 mb-1">Empresa</label>
                            <select name="empresa_id" class="select select-sm w-full">
                                <option value="">Todas</option>
                                @foreach($empresas as $emp)
                                    <option value="{{ $emp->id }}" {{ request('empresa_id') == $emp->id ? 'selected' : '' }}>{{ $emp->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div>
                            <label class="block text-xs font-semibold text-base-content/60 mb-1">Fecha inicio</label>
                            <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="input input-sm w-full">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-base-content/60 mb-1">Fecha fin</label>
                            <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="input input-sm w-full">
                        </div>

                        <div class="xl:col-span-2">
                            <label class="block text-xs font-semibold text-base-content/60 mb-1">Búsqueda</label>
                            <input type="text" name="keyword" value="{{ request('keyword') }}" class="input input-sm w-full" placeholder="Nombre, código, NIT, email...">
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary gap-1">Buscar</button>
                            <a href="{{ route('proveedores.index') }}" class="btn btn-sm btn-outline gap-1">Limpiar</a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="card bg-base-100 shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr class="bg-base-200/50 text-xs uppercase">
                                <th>#</th>
                                <th>Código Alterno</th>
                                <th>Nombre</th>
                                <th>NIT/RUT</th>
                                <th>Teléfono</th>
                                <th>Email</th>
                                <th>Empresa</th>
                                <th>Fecha Creación</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($proveedores as $p)
                            <tr class="hover:bg-base-200/30 transition-colors">
                                <td class="font-mono text-xs text-base-content/60">{{ $p->id }}</td>
                                <td class="font-mono font-semibold">{{ $p->codigo_alterno }}</td>
                                <td>{{ $p->nombre }}</td>
                                <td class="text-xs">{{ $p->nit_rut ?? '—' }}</td>
                                <td class="text-xs">{{ $p->telefono ?? '—' }}</td>
                                <td class="text-xs">{{ $p->email ?? '—' }}</td>
                                <td>
                                    <span class="badge badge-sm font-semibold"
                                          style="background-color: {{ $p->empresa->color_hex ?? '#6b7280' }}20; color: {{ $p->empresa->color_hex ?? '#6b7280' }};">
                                        {{ $p->empresa->nombre ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-xs text-base-content/60">{{ $p->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($p->activo)
                                        <span class="badge badge-sm badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-sm badge-error">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('proveedores.edit', $p->id) }}" class="btn btn-ghost btn-xs btn-square" title="Editar">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-info" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        <form method="POST" action="{{ route('proveedores.destroy', $p->id) }}" class="inline" onsubmit="return confirm('¿Está seguro de eliminar este proveedor?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-ghost btn-xs btn-square">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-12">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-base-content/20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                                    <p class="text-base-content/40 text-sm">No se encontraron proveedores</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($proveedores->hasPages())
                <div class="px-4 py-3 border-t border-base-200">
                    {{ $proveedores->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    @include('proveedores._modal_importar')
</x-app-layout>
