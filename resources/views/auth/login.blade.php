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
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                           class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-400 focus:border-gray-400 focus:ring-2 focus:ring-gray-200 outline-none transition-all duration-300"
                           placeholder="••••••••">
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
