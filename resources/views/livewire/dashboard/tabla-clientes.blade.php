<div>
    {{-- ========== HEADER ========== --}}
    <div class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Clientes Registrados</h1>
                    <p class="text-sm text-gray-600 mt-0.5">
                        <span class="font-semibold">{{ $totalClientes }}</span> clientes encontrados
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('clientes.crear') }}" class="btn btn-sm btn-neutral gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        Nuevo
                    </a>
                    @can('export', App\Models\Cliente::class)
                    <button wire:click="exportarCsv" class="btn btn-sm btn-success gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Exportar CSV
                    </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 space-y-4">

        {{-- Flash --}}
        @if(session()->has('message'))
            <div class="alert alert-success shadow-md text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        {{-- ========== FILTROS ========== --}}
        <div class="card bg-base-100 shadow">
            <div class="card-body p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-3 items-end">
                    {{-- Búsqueda --}}
                    <div class="xl:col-span-2">
                        <label class="block text-xs font-semibold text-base-content/60 mb-1">Buscar</label>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-3 top-1/2 -translate-y-1/2 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            <input type="text" wire:model.live.debounce.300ms="busqueda" class="input input-sm w-full pl-9" placeholder="Nombre, documento, establecimiento...">
                        </div>
                    </div>

                    {{-- Empresa --}}
                    <div>
                        <label class="block text-xs font-semibold text-base-content/60 mb-1">Empresa</label>
                        <select wire:model.live="empresa_id" class="select select-sm w-full">
                            <option value="">Todas</option>
                            @foreach($empresas as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Vendedor --}}
                    @if($esAdmin)
                    <div>
                        <label class="block text-xs font-semibold text-base-content/60 mb-1">Vendedor</label>
                        <select wire:model.live="vendedor_id" class="select select-sm w-full">
                            <option value="">Todos</option>
                            @foreach($vendedores as $v)
                                <option value="{{ $v->id }}">{{ $v->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    {{-- Fecha desde --}}
                    <div>
                        <label class="block text-xs font-semibold text-base-content/60 mb-1">Desde</label>
                        <input type="date" wire:model.live="fecha_desde" class="input input-sm w-full">
                    </div>

                    {{-- Fecha hasta --}}
                    <div>
                        <label class="block text-xs font-semibold text-base-content/60 mb-1">Hasta</label>
                        <input type="date" wire:model.live="fecha_hasta" class="input input-sm w-full">
                    </div>

                    {{-- Tipo negocio --}}
                    <div>
                        <label class="block text-xs font-semibold text-base-content/60 mb-1">Tipo Negocio</label>
                        <select wire:model.live="tipo_negocio" class="select select-sm w-full">
                            <option value="">Todos</option>
                            <option value="mayorista">Mayorista</option>
                            <option value="supermercado">Supermercado</option>
                            <option value="tienda">Tienda</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    {{-- Limpiar filtros (solo si hay algún filtro activo) --}}
                    @if($busqueda || $empresa_id || $vendedor_id || $fecha_desde || $fecha_hasta || $tipo_negocio)
                    <div>
                        <button wire:click="limpiarFiltros" class="btn btn-sm btn-outline btn-error w-full gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            Limpiar
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ========== TABLA ========== --}}
        <div class="card bg-base-100 shadow overflow-hidden">
            {{-- Loading overlay --}}
            <div wire:loading.delay class="absolute inset-0 bg-base-100/60 z-10 flex items-center justify-center">
                <span class="loading loading-spinner loading-md text-primary"></span>
            </div>

            <div class="overflow-x-auto">
                <table class="table table-sm">
                    <thead>
                        <tr class="bg-base-200/50 text-xs uppercase">
                            <th>#ID</th>
                            <th>Empresa</th>
                            <th>Cliente</th>
                            <th>Documento</th>
                            <th class="hidden md:table-cell">Vendedor</th>
                            <th class="hidden lg:table-cell">Tipo Negocio</th>
                            <th class="hidden sm:table-cell">Fecha</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $cliente)
                        <tr class="hover:bg-base-200/30 transition-colors">
                            <td class="font-mono text-xs text-base-content/60">{{ $cliente->id }}</td>
                            <td>
                                <span class="badge badge-sm font-semibold"
                                      style="background-color: {{ $cliente->empresa->color_hex ?? '#6b7280' }}20; color: {{ $cliente->empresa->color_hex ?? '#6b7280' }}; border-color: {{ $cliente->empresa->color_hex ?? '#6b7280' }}40;">
                                    {{ $cliente->empresa->nombre ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <p class="font-semibold text-sm leading-tight">{{ $cliente->nombre_razon_social }}</p>
                                    @if($cliente->nombre_establecimiento)
                                        <p class="text-xs text-base-content/50">{{ $cliente->nombre_establecimiento }}</p>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="text-xs font-medium">{{ $cliente->tipo_documento }}</span>
                                <span class="text-xs text-base-content/60">{{ $cliente->numero_documento }}</span>
                            </td>
                            <td class="hidden md:table-cell text-xs">{{ $cliente->vendedor->name ?? 'N/A' }}</td>
                            <td class="hidden lg:table-cell">
                                @php
                                    $badgeColors = [
                                        'mayorista' => 'badge-info',
                                        'supermercado' => 'badge-warning',
                                        'tienda' => 'badge-success',
                                        'otro' => 'badge-neutral',
                                    ];
                                @endphp
                                <span class="badge badge-sm {{ $badgeColors[$cliente->tipo_negocio] ?? 'badge-ghost' }}">
                                    {{ $cliente->tipoNegocioTexto() }}
                                </span>
                            </td>
                            <td class="hidden sm:table-cell text-xs text-base-content/60">
                                {{ $cliente->created_at->format('d/m/Y') }}
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-1">
                                    {{-- Ver detalle (ojito) --}}
                                    <div class="tooltip" data-tip="Ver detalle">
                                        <button onclick="document.getElementById('modal-{{ $cliente->id }}').showModal()" class="btn btn-ghost btn-xs btn-square">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-info" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </button>
                                    </div>

                                    {{-- PDF: Ver / Descargar (formato CTA-FMT-001 con metadatos) --}}
                                    <div class="tooltip" data-tip="Ver PDF (CTA-FMT-001)">
                                        <a href="{{ route('clientes.pdf.ver', $cliente->id) }}" target="_blank" class="btn btn-ghost btn-xs btn-square">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                        </a>
                                    </div>
                                    <div class="tooltip" data-tip="Descargar PDF (CTA-FMT-001)">
                                        <a href="{{ route('clientes.pdf.descargar', $cliente->id) }}" class="btn btn-ghost btn-xs btn-square" download>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                        </a>
                                    </div>

                                    {{-- Archivar (solo admin) --}}
                                    @if($esAdmin)
                                    <div class="tooltip" data-tip="Archivar">
                                        <button wire:click="archivarCliente({{ $cliente->id }})"
                                                wire:confirm="¿Está seguro de archivar este cliente? Esta acción es reversible."
                                                class="btn btn-ghost btn-xs btn-square">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                                        </button>
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Modal detalle --}}
                        <dialog id="modal-{{ $cliente->id }}" class="modal">
                            <div class="modal-box max-w-2xl">
                                <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button></form>
                                <h3 class="text-lg font-bold text-primary">{{ $cliente->nombre_razon_social }}</h3>
                                <p class="text-xs text-base-content/50 mb-4">ID #{{ $cliente->id }} &bull; {{ $cliente->created_at->format('d/m/Y H:i') }}</p>

                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div><span class="font-semibold text-xs text-base-content/60">Empresa:</span><br>{{ $cliente->empresa->nombre ?? 'N/A' }}</div>
                                    <div><span class="font-semibold text-xs text-base-content/60">Vendedor:</span><br>{{ $cliente->vendedor->name ?? 'N/A' }}</div>
                                    <div><span class="font-semibold text-xs text-base-content/60">Tipo Doc:</span><br>{{ $cliente->tipo_documento }} {{ $cliente->numero_documento }}</div>
                                    <div><span class="font-semibold text-xs text-base-content/60">Tipo Solicitud:</span><br>{{ ucfirst($cliente->tipo_solicitud) }}</div>
                                    <div><span class="font-semibold text-xs text-base-content/60">Establecimiento:</span><br>{{ $cliente->nombre_establecimiento ?? '—' }}</div>
                                    <div><span class="font-semibold text-xs text-base-content/60">Tipo Negocio:</span><br>{{ $cliente->tipoNegocioTexto() }}</div>
                                    <div><span class="font-semibold text-xs text-base-content/60">Dirección:</span><br>{{ $cliente->direccion }}, {{ $cliente->barrio ?? '' }}</div>
                                    <div><span class="font-semibold text-xs text-base-content/60">Ciudad:</span><br>{{ $cliente->ciudad_departamento }}</div>
                                    <div><span class="font-semibold text-xs text-base-content/60">Celular:</span><br>{{ $cliente->celular }}</div>
                                    <div><span class="font-semibold text-xs text-base-content/60">Correo:</span><br>{{ $cliente->correo_electronico ?? '—' }}</div>
                                    <div><span class="font-semibold text-xs text-base-content/60">Zona:</span><br>{{ $cliente->zona ?? '—' }}</div>
                                    <div><span class="font-semibold text-xs text-base-content/60">Contado:</span><br>{{ $cliente->cliente_contado ? 'Sí' : 'No' }}</div>
                                </div>

                                @if($cliente->cedula_pdf_url || $cliente->rut_pdf_url)
                                <div class="mt-4 space-y-1">
                                    <span class="font-semibold text-xs text-base-content/60">Documentos:</span>
                                    <div class="flex flex-wrap gap-2">
                                        @if($cliente->cedula_pdf_url)
                                            <a href="{{ route('clientes.documento.descargar', ['id' => $cliente->id, 'tipo' => 'cedula']) }}" target="_blank" class="btn btn-xs btn-outline btn-primary">
                                                Cédula PDF
                                            </a>
                                        @endif
                                        @if($cliente->rut_pdf_url)
                                            <a href="{{ route('clientes.documento.descargar', ['id' => $cliente->id, 'tipo' => 'rut']) }}" target="_blank" class="btn btn-xs btn-outline btn-primary">
                                                RUT PDF
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                @if($cliente->foto_url)
                                <div class="mt-4">
                                    <span class="font-semibold text-xs text-base-content/60">Foto del cliente:</span>
                                    <img src="{{ $cliente->foto_url }}" alt="Foto" class="w-28 h-28 object-cover rounded-xl mt-1 border">
                                </div>
                                @endif

                                @if($cliente->firma_base64)
                                <div class="mt-4">
                                    <span class="font-semibold text-xs text-base-content/60">Firma:</span>
                                    <img src="{{ $cliente->firma_base64 }}" alt="Firma" class="max-w-[200px] h-auto mt-1 border rounded bg-white p-1">
                                </div>
                                @endif

                                <div class="mt-4 p-2 bg-base-200 rounded text-xs text-base-content/50">
                                    IP: {{ $cliente->ip_dispositivo ?? 'N/A' }} &bull;
                                    QR: {{ Str::limit($cliente->codigo_verificacion_qr, 12) }}
                                </div>
                            </div>
                            <form method="dialog" class="modal-backdrop"><button>close</button></form>
                        </dialog>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-12">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-base-content/20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                <p class="text-base-content/40 text-sm">No se encontraron clientes</p>
                                @if($busqueda || $empresa_id || $vendedor_id || $fecha_desde || $fecha_hasta || $tipo_negocio)
                                    <button wire:click="limpiarFiltros" class="btn btn-sm btn-outline btn-primary mt-3">Limpiar filtros</button>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($clientes->hasPages())
            <div class="px-4 py-3 border-t border-base-200">
                {{ $clientes->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
