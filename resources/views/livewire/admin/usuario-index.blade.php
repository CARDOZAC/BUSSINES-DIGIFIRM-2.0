<div>
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Gestión de Usuarios</h1>
                <p class="text-sm text-gray-600 mt-0.5">Crear y administrar vendedores</p>
            </div>
            <button wire:click="abrirCrear" class="btn btn-sm btn-neutral gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nuevo Vendedor
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-4">
        @if(session()->has('message'))
            <div class="alert alert-success shadow-md text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-error shadow-md text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="card bg-base-100 shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th class="text-xs">Nombre</th>
                            <th class="text-xs">Email</th>
                            <th class="text-xs hidden md:table-cell">Código</th>
                            <th class="text-xs hidden md:table-cell">Empresa</th>
                            <th class="text-xs hidden lg:table-cell">Zona</th>
                            <th class="text-xs hidden lg:table-cell">Último acceso</th>
                            <th class="text-xs hidden xl:table-cell">Ubicación</th>
                            <th class="text-xs">Estado</th>
                            <th class="text-xs">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $u)
                        <tr>
                            <td class="font-medium">{{ $u->name }}</td>
                            <td class="text-sm">{{ $u->email }}</td>
                            <td class="text-sm hidden md:table-cell font-mono">{{ $u->codigo_vendedor ?? '—' }}</td>
                            <td class="text-sm hidden md:table-cell">{{ $u->empresa?->nombre ?? '—' }}</td>
                            <td class="text-sm hidden lg:table-cell">{{ $u->zona ?? '—' }}</td>
                            <td class="text-xs hidden lg:table-cell">
                                @if($u->ultimo_login)
                                    {{ $u->ultimo_login->format('d/m/Y H:i') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-xs hidden xl:table-cell max-w-[140px] truncate" title="{{ $u->ubicacion_ultimo_login }}">
                                {{ $u->ubicacion_ultimo_login ?? '—' }}
                            </td>
                            <td>
                                @if($u->active)
                                    <span class="badge badge-success badge-sm">Activo</span>
                                @else
                                    <span class="badge badge-ghost badge-sm">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <button wire:click="abrirEditar({{ $u->id }})" class="btn btn-ghost btn-xs">
                                        Editar
                                    </button>
                                    @if(!$u->hasRole('super_admin'))
                                    <button wire:click="toggleActivo({{ $u->id }})" class="btn btn-ghost btn-xs">
                                        {{ $u->active ? 'Desactivar' : 'Activar' }}
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-base-200">
                {{ $usuarios->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Crear/Editar --}}
    @if($mostrarModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="cerrarModal"></div>

            <div class="relative bg-base-100 rounded-2xl shadow-xl max-w-lg w-full p-6">
                <h3 class="text-lg font-bold mb-4">{{ $usuarioId ? 'Editar Usuario' : 'Nuevo Vendedor' }}</h3>

                <form wire:submit="guardar" class="space-y-4">
                    <div>
                        <label class="label"><span class="label-text">Nombre *</span></label>
                        <input type="text" wire:model="name" class="input input-bordered w-full" placeholder="Nombre completo">
                        @error('name') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label"><span class="label-text">Correo electrónico *</span></label>
                        <input type="email" wire:model="email" class="input input-bordered w-full" placeholder="vendedor@empresa.com">
                        @error('email') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label"><span class="label-text">Contraseña {{ $usuarioId ? '(dejar vacío para no cambiar)' : '*' }}</span></label>
                        <input type="password" wire:model="password" class="input input-bordered w-full" placeholder="••••••••" autocomplete="new-password">
                        @error('password') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="label"><span class="label-text">Empresa *</span></label>
                        <select wire:model="empresa_id" class="select select-bordered w-full">
                            <option value="">Seleccione...</option>
                            @foreach($empresas as $e)
                                <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                            @endforeach
                        </select>
                        @error('empresa_id') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="label"><span class="label-text">Código vendedor</span></label>
                            <input type="text" wire:model="codigo_vendedor" class="input input-bordered w-full" placeholder="VEN-001">
                            @error('codigo_vendedor') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label"><span class="label-text">Zona</span></label>
                            <input type="text" wire:model="zona" class="input input-bordered w-full" placeholder="Zona Norte">
                        </div>
                    </div>

                    <div>
                        <label class="label"><span class="label-text">Celular</span></label>
                        <input type="text" wire:model="celular" class="input input-bordered w-full" placeholder="3001234567">
                    </div>

                    <div class="form-control">
                        <label class="label cursor-pointer justify-start gap-2">
                            <input type="checkbox" wire:model="active" class="checkbox checkbox-primary">
                            <span class="label-text">Usuario activo</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" wire:click="cerrarModal" class="btn btn-ghost">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
