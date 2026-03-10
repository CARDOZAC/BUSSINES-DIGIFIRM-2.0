<x-guest-layout>
@php
    $logos = [
        ['file' => 'AJAR_DISTRIBUCIONES-removebg-preview.png', 'alt' => 'AJAR'],
        ['file' => 'RINVAL_SAS-removebg-preview.png', 'alt' => 'RINVAL'],
        ['file' => 'LOGODISTMASIVOS.-removebg-preview.png', 'alt' => 'DISTMASIVOS'],
    ];
@endphp
<div class="min-h-screen flex items-center justify-center overflow-hidden relative bg-gray-100"
     x-data="{ loaded: false, latitud: null, longitud: null }"
     x-init="
        setTimeout(() => loaded = true, 100);
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (pos) => { latitud = pos.coords.latitude; longitud = pos.coords.longitude; },
                () => {},
                { enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }
            );
        }
     ">

    {{-- Card central: estilo escalado de grises --}}
    <div class="relative z-10 w-full max-w-md mx-4"
         x-show="loaded"
         x-transition:enter="transition ease-out duration-700"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0">

        <div class="rounded-2xl bg-white border border-gray-200 shadow-xl p-8">

            {{-- Logos: contenedor con fondo gris para que no se camuflen --}}
            <div class="flex justify-center gap-4 mb-6">
                @foreach($logos as $logo)
                    @if(file_exists(base_path('index/' . $logo['file'])))
                        <div class="flex items-center justify-center w-20 h-16 rounded-lg bg-gray-100 border border-gray-200 p-2 shadow-sm">
                            <img src="{{ route('logos.serve', $logo['file']) }}" alt="{{ $logo['alt'] }}"
                                 class="max-h-full max-w-full object-contain">
                        </div>
                    @endif
                @endforeach
            </div>

            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 text-center mb-1 tracking-tight">
                Sistema de Creación de Clientes
            </h1>
            <p class="text-center text-gray-600 text-sm mb-6 font-light min-h-[1.5rem]">
                <span class="inline-block typewriter">SOMOS EQUIPO, SOMOS FUERZA</span>
            </p>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="latitud" :value="latitud">
                <input type="hidden" name="longitud" :value="longitud">

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-medium text-gray-600 mb-1.5">Correo electrónico</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-400 focus:border-gray-400 focus:ring-2 focus:ring-gray-200 outline-none transition-all duration-300"
                           placeholder="usuario@empresa.com">
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-medium text-gray-600 mb-1.5">Contraseña</label>
                    <div class="relative" x-data="{ show: false }">
                        <input id="password" name="password" :type="show ? 'text' : 'password'" required autocomplete="current-password"
                               class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-400 focus:border-gray-400 focus:ring-2 focus:ring-gray-200 outline-none transition-all duration-300 pr-12"
                               placeholder="••••••••">
                        <button type="button" x-on:click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember --}}
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-gray-600 focus:ring-gray-400">
                    <span class="text-sm text-gray-600">Recordarme</span>
                </label>

                {{-- Submit: botón en escala de grises --}}
                <button type="submit"
                        class="w-full py-3.5 rounded-xl font-semibold text-white text-sm tracking-wide
                               bg-gray-700 hover:bg-gray-800 transition-all duration-300 hover:-translate-y-0.5 active:translate-y-0 shadow-md">
                    Iniciar Sesión
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-gray-500 mt-6">
            &copy; {{ date('Y') }} Sistema de Creación de Clientes
        </p>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }

    .typewriter {
        display: inline-block;
        overflow: hidden;
        white-space: nowrap;
        border-right: 2px solid rgba(75,85,99,0.5);
        animation: typewriter 3s steps(25) 1s forwards, blink 0.6s step-end infinite;
        width: 0;
    }
    @keyframes typewriter {
        to { width: 100%; }
    }
    @keyframes blink {
        50% { border-color: transparent; }
    }
</style>
</x-guest-layout>
