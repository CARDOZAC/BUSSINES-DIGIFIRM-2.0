<div>
    {{-- Fondo blanco/gris --}}
    <div class="min-h-[calc(100vh-3.5rem)] bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
            {{-- Saludo dinámico --}}
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                {{ $this->saludo }}
            </h1>
            <p class="text-gray-600 text-sm mb-8 font-light italic">
                "{{ $this->versiculoDelDia }}"
            </p>

            {{-- Cards de acceso rápido --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Crear Cliente --}}
                <a href="{{ route('clientes.crear') }}"
                   class="group flex items-center gap-4 p-5 rounded-2xl bg-white border border-gray-200 shadow-sm hover:shadow-md hover:border-gray-300 transition-all duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                        <span class="text-2xl">📝</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Crear Cliente</h2>
                        <p class="text-sm text-gray-600">Registrar nuevo cliente con firma digital</p>
                    </div>
                </a>

                {{-- Ver Clientes --}}
                <a href="{{ route('clientes.index') }}"
                   class="group flex items-center gap-4 p-5 rounded-2xl bg-white border border-gray-200 shadow-sm hover:shadow-md hover:border-gray-300 transition-all duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                        <span class="text-2xl">👥</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Ver Clientes</h2>
                        <p class="text-sm text-gray-600">Consultar clientes registrados</p>
                    </div>
                </a>

                @if(Auth::user()->esAdminCartera() || Auth::user()->hasRole('super_admin'))
                {{-- Exportar --}}
                <a href="{{ route('clientes.index') }}?exportar=1"
                   class="group flex items-center gap-4 p-5 rounded-2xl bg-white border border-gray-200 shadow-sm hover:shadow-md hover:border-gray-300 transition-all duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                        <span class="text-2xl">📊</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Exportar</h2>
                        <p class="text-sm text-gray-600">Exportar clientes a CSV</p>
                    </div>
                </a>

                {{-- Auditoría --}}
                <a href="{{ route('admin.auditoria') }}"
                   class="group flex items-center gap-4 p-5 rounded-2xl bg-white border border-gray-200 shadow-sm hover:shadow-md hover:border-gray-300 transition-all duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                        <span class="text-2xl">🔍</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Auditoría</h2>
                        <p class="text-sm text-gray-600">Registro de actividad del sistema</p>
                    </div>
                </a>

                {{-- Usuarios (solo super_admin) --}}
                @if(Auth::user()->hasRole('super_admin'))
                <a href="{{ route('admin.usuarios') }}"
                   class="group flex items-center gap-4 p-5 rounded-2xl bg-white border border-gray-200 shadow-sm hover:shadow-md hover:border-gray-300 transition-all duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center group-hover:bg-gray-200 transition-colors">
                        <span class="text-2xl">👤</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Usuarios</h2>
                        <p class="text-sm text-gray-600">Gestionar vendedores y accesos</p>
                    </div>
                </a>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>
