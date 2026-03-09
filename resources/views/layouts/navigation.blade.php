<nav x-data="{ open: false }" class="bg-base-100 border-b border-base-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 shrink-0">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-primary-content" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="hidden sm:block text-sm font-bold text-primary">R&V</span>
                </a>

                <div class="hidden sm:flex sm:items-center sm:ms-8 gap-1">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary' : 'text-base-content/60 hover:text-base-content hover:bg-base-200' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('clientes.crear') }}"
                       class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('clientes.crear') ? 'bg-primary/10 text-primary' : 'text-base-content/60 hover:text-base-content hover:bg-base-200' }}">
                        Nuevo Cliente
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center gap-3">
                @if(Auth::user()->empresa)
                    <span class="text-xs px-2 py-1 bg-base-200 rounded-md text-base-content/60 font-medium">
                        {{ Auth::user()->empresa->nombre }}
                    </span>
                @endif

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-sm text-base-content/70 hover:bg-base-200 transition-colors">
                            <div class="w-7 h-7 bg-primary/10 rounded-full flex items-center justify-center">
                                <span class="text-xs font-bold text-primary">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <span class="hidden md:block font-medium">{{ Auth::user()->name }}</span>
                            <svg class="h-3.5 w-3.5 text-base-content/40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-base-200">
                            <p class="text-xs text-base-content/50">{{ Auth::user()->email }}</p>
                            <p class="text-xs font-medium text-primary">{{ Auth::user()->esAdminCartera() ? 'Admin Cartera' : 'Vendedor' }}</p>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">Mi Perfil</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Cerrar Sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button x-on:click="open = ! open" class="p-2 rounded-lg text-base-content/60 hover:bg-base-200 transition-colors">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-base-200">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary/10 text-primary font-semibold' : 'text-base-content/70' }}">
                Dashboard
            </a>
            <a href="{{ route('clientes.crear') }}" class="block px-3 py-2 text-sm rounded-lg {{ request()->routeIs('clientes.crear') ? 'bg-primary/10 text-primary font-semibold' : 'text-base-content/70' }}">
                Nuevo Cliente
            </a>
        </div>

        <div class="pt-3 pb-3 border-t border-base-200 px-4">
            <div class="mb-2">
                <p class="text-sm font-medium text-base-content">{{ Auth::user()->name }}</p>
                <p class="text-xs text-base-content/50">{{ Auth::user()->email }}</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm text-base-content/70 rounded-lg">Mi Perfil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-3 py-2 text-sm text-error rounded-lg">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</nav>
