<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('proveedores.index') }}" class="btn btn-ghost btn-sm btn-square">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            </a>
            <h1 class="text-xl font-bold">Editar Proveedor: {{ $proveedor->codigo_alterno }}</h1>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <form method="POST" action="{{ route('proveedores.update', $proveedor->id) }}">
                        @csrf
                        @method('PUT')

                        @if(!Auth::user()->hasAnyRole(['super_admin', 'admin-cartera']))
                            <input type="hidden" name="empresa_id" value="{{ $proveedor->empresa_id }}">
                            <div class="form-control">
                                <label class="label"><span class="label-text">Empresa</span></label>
                                <input type="text" class="input input-bordered bg-base-200" value="{{ $proveedor->empresa->nombre ?? 'N/A' }}" disabled readonly>
                            </div>
                        @else
                            <div class="form-control">
                                <label class="label"><span class="label-text">Empresa <span class="text-error">*</span></span></label>
                                <select name="empresa_id" class="select select-bordered w-full" required>
                                    @foreach($empresas as $emp)
                                        <option value="{{ $emp->id }}" {{ old('empresa_id', $proveedor->empresa_id) == $emp->id ? 'selected' : '' }}>{{ $emp->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('empresa_id')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        @endif

                        <div class="form-control">
                            <label class="label"><span class="label-text">Código Alterno</span></label>
                            <input type="text" class="input input-bordered bg-base-200" value="{{ $proveedor->codigo_alterno }}" disabled readonly>
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text">Nombre <span class="text-error">*</span></span></label>
                            <input type="text" name="nombre" value="{{ old('nombre', $proveedor->nombre) }}" class="input input-bordered w-full" required maxlength="255">
                            @error('nombre')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text">NIT/RUT</span></label>
                            <input type="text" name="nit_rut" value="{{ old('nit_rut', $proveedor->nit_rut) }}" class="input input-bordered w-full" maxlength="50">
                            @error('nit_rut')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label"><span class="label-text">Teléfono</span></label>
                                <input type="text" name="telefono" value="{{ old('telefono', $proveedor->telefono) }}" class="input input-bordered w-full" maxlength="20">
                                @error('telefono')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text">Email</span></label>
                                <input type="email" name="email" value="{{ old('email', $proveedor->email) }}" class="input input-bordered w-full" maxlength="100">
                                @error('email')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text">Ciudad</span></label>
                            <input type="text" name="ciudad" value="{{ old('ciudad', $proveedor->ciudad) }}" class="input input-bordered w-full" maxlength="100">
                            @error('ciudad')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="form-control">
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="checkbox" name="activo" value="1" class="checkbox checkbox-primary" {{ old('activo', $proveedor->activo) ? 'checked' : '' }}>
                                <span class="label-text">Proveedor activo</span>
                            </label>
                        </div>

                        <div class="form-control">
                            <label class="label"><span class="label-text">Observaciones</span></label>
                            <textarea name="observaciones" class="textarea textarea-bordered w-full" rows="3" maxlength="500">{{ old('observaciones', $proveedor->observaciones) }}</textarea>
                            @error('observaciones')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="card-actions justify-end mt-4">
                            <a href="{{ route('proveedores.index') }}" class="btn btn-ghost">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar Proveedor</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
