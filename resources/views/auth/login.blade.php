<x-guest-layout>
<div class="relative min-h-screen flex overflow-hidden" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 150)">

    {{-- ===== PANEL IZQUIERDO: FONDO ANIMADO CIRCUITOS ===== --}}
    <div class="hidden lg:flex lg:w-3/5 relative overflow-hidden bg-gradient-to-br from-[#0a1628] via-[#0f2847] to-[#1e3a5f]"
         x-show="loaded"
         x-transition:enter="transition ease-out duration-1000"
         x-transition:enter-start="opacity-0 -translate-x-12"
         x-transition:enter-end="opacity-100 translate-x-0">
        {{-- SVG Circuit Pattern --}}
        <svg class="absolute inset-0 w-full h-full opacity-10" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="circuit" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
                    <circle cx="50" cy="50" r="2" fill="#3b82f6"/>
                    <line x1="50" y1="0" x2="50" y2="48" stroke="#3b82f6" stroke-width="0.5"/>
                    <line x1="50" y1="52" x2="50" y2="100" stroke="#3b82f6" stroke-width="0.5"/>
                    <line x1="0" y1="50" x2="48" y2="50" stroke="#3b82f6" stroke-width="0.5"/>
                    <line x1="52" y1="50" x2="100" y2="50" stroke="#3b82f6" stroke-width="0.5"/>
                    <circle cx="0" cy="0" r="1.5" fill="#3b82f6"/>
                    <circle cx="100" cy="0" r="1.5" fill="#3b82f6"/>
                    <circle cx="0" cy="100" r="1.5" fill="#3b82f6"/>
                    <circle cx="100" cy="100" r="1.5" fill="#3b82f6"/>
                    <line x1="50" y1="50" x2="75" y2="25" stroke="#3b82f6" stroke-width="0.3"/>
                    <line x1="50" y1="50" x2="25" y2="75" stroke="#3b82f6" stroke-width="0.3"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#circuit)"/>
        </svg>

        {{-- Animated glowing orbs --}}
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-cyan-500/8 rounded-full blur-3xl animate-pulse-slow-delayed"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-500/5 rounded-full blur-3xl animate-float"></div>

        {{-- Animated circuit lines (data flow) --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="circuit-line circuit-line-1"></div>
            <div class="circuit-line circuit-line-2"></div>
            <div class="circuit-line circuit-line-3"></div>
            <div class="circuit-line circuit-line-4"></div>
            <div class="circuit-line circuit-line-5"></div>
        </div>
        {{-- Glowing circuit nodes --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="circuit-node circuit-node-1"></div>
            <div class="circuit-node circuit-node-2"></div>
            <div class="circuit-node circuit-node-3"></div>
            <div class="circuit-node circuit-node-4"></div>
            <div class="circuit-node circuit-node-5"></div>
        </div>

        {{-- Branding central --}}
        <div class="relative z-10 flex flex-col items-center justify-center w-full px-12 text-white"
             x-show="loaded"
             x-transition:enter="transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-800 delay-200"
             x-transition:enter-start="opacity-0 -translate-x-12 scale-[0.98]"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100">
            <div class="mb-6">
                <div class="w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20 shadow-2xl">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl font-bold tracking-tight mb-2">Digital Clientes</h1>
            <p class="text-lg text-blue-200/80 font-light mb-8">Grupo R&V</p>
            <div class="max-w-sm text-center">
                <p class="text-sm text-blue-300/60 leading-relaxed">
                    Sistema de registro digital de clientes con firma electrónica, generación de PDF y trazabilidad completa.
                </p>
            </div>

            {{-- Feature indicators --}}
            <div class="mt-10 grid grid-cols-3 gap-6 text-center">
                <div class="group">
                    <div class="w-10 h-10 mx-auto bg-white/5 rounded-xl flex items-center justify-center border border-white/10 group-hover:border-cyan-400/40 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-400/70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    </div>
                    <p class="text-xs text-blue-300/50 mt-2">Firma Digital</p>
                </div>
                <div class="group">
                    <div class="w-10 h-10 mx-auto bg-white/5 rounded-xl flex items-center justify-center border border-white/10 group-hover:border-cyan-400/40 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-400/70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <p class="text-xs text-blue-300/50 mt-2">PDF Inmutable</p>
                </div>
                <div class="group">
                    <div class="w-10 h-10 mx-auto bg-white/5 rounded-xl flex items-center justify-center border border-white/10 group-hover:border-cyan-400/40 transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-cyan-400/70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    <p class="text-xs text-blue-300/50 mt-2">Trazabilidad</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== PANEL DERECHO: FORMULARIO LOGIN ===== --}}
    <div class="w-full lg:w-2/5 flex items-center justify-center bg-gradient-to-b from-gray-50 to-gray-100 relative">
        {{-- Subtle pattern overlay for mobile --}}
        <div class="lg:hidden absolute inset-0 bg-gradient-to-br from-[#0a1628]/95 via-[#0f2847]/95 to-[#1e3a5f]/95">
            <svg class="absolute inset-0 w-full h-full opacity-5" xmlns="http://www.w3.org/2000/svg">
                <defs><pattern id="circuit-m" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse"><circle cx="40" cy="40" r="1.5" fill="#3b82f6"/><line x1="40" y1="0" x2="40" y2="38" stroke="#3b82f6" stroke-width="0.4"/><line x1="40" y1="42" x2="40" y2="80" stroke="#3b82f6" stroke-width="0.4"/></pattern></defs>
                <rect width="100%" height="100%" fill="url(#circuit-m)"/>
            </svg>
        </div>

        <div class="w-full max-w-sm mx-auto px-6 py-8 relative z-10"
             x-show="loaded"
             x-transition:enter="transition ease-[cubic-bezier(0.32,0.72,0,1)] duration-700 delay-400"
             x-transition:enter-start="opacity-0 translate-x-16 scale-95"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100">

            {{-- Mobile logo --}}
            <div class="lg:hidden text-center mb-8">
                <div class="w-14 h-14 mx-auto bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/20 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-white">Digital Clientes R&V</h1>
            </div>

            {{-- Card: glassmorphism iOS-style --}}
            <div class="bg-white/95 lg:bg-white/90 backdrop-blur-2xl rounded-3xl shadow-2xl shadow-black/5 p-8 border border-white/60 lg:border-gray-200/50 ring-1 ring-black/5">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Bienvenido</h2>
                    <p class="text-sm text-gray-500 mt-1">Ingrese sus credenciales para continuar</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div class="space-y-1.5">
                        <label for="email" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Correo electrónico</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                                   class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 outline-none"
                                   placeholder="usuario@empresa.com">
                        </div>
                        @error('email')
                            <p class="text-xs text-red-500 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01" /></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Contraseña</label>
                        <div class="relative group" x-data="{ showPassword: false }">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </div>
                            <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required autocomplete="current-password"
                                   class="w-full pl-10 pr-11 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200 outline-none"
                                   placeholder="••••••••">
                            <button type="button" x-on:click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-primary/30 transition-colors">
                            <span class="text-xs text-gray-500">Recordarme</span>
                        </label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full py-3 bg-gradient-to-r from-[#1e3a5f] to-[#2d5a8e] text-white font-semibold rounded-xl shadow-lg shadow-primary/25 hover:shadow-xl hover:shadow-primary/30 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 text-sm tracking-wide">
                        Iniciar Sesión
                    </button>
                </form>
            </div>

            {{-- Footer --}}
            <p class="text-center text-xs mt-6 lg:text-gray-400 text-gray-400/60">
                &copy; {{ date('Y') }} Grupo R&V — Todos los derechos reservados
            </p>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }

    @keyframes pulse-slow {
        0%, 100% { opacity: 0.3; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(1.1); }
    }
    @keyframes pulse-slow-delayed {
        0%, 100% { opacity: 0.2; transform: scale(1.05); }
        50% { opacity: 0.5; transform: scale(0.95); }
    }
    @keyframes float {
        0%, 100% { transform: translate(-50%, -50%) scale(1); }
        33% { transform: translate(-48%, -52%) scale(1.05); }
        66% { transform: translate(-52%, -48%) scale(0.95); }
    }
    .animate-pulse-slow { animation: pulse-slow 6s ease-in-out infinite; }
    .animate-pulse-slow-delayed { animation: pulse-slow-delayed 8s ease-in-out infinite 2s; }
    .animate-float { animation: float 10s ease-in-out infinite; }

    @keyframes circuit-flow {
        0% { transform: translateY(-100%); opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { transform: translateY(100vh); opacity: 0; }
    }
    .circuit-line {
        position: absolute;
        width: 1px;
        height: 80px;
        background: linear-gradient(to bottom, transparent, rgba(59,130,246,0.4), transparent);
        animation: circuit-flow 4s linear infinite;
    }
    .circuit-line-1 { left: 15%; animation-delay: 0s; animation-duration: 5s; }
    .circuit-line-2 { left: 40%; animation-delay: 1.2s; animation-duration: 4.5s; }
    .circuit-line-3 { left: 65%; animation-delay: 2.5s; animation-duration: 6s; }
    .circuit-line-4 { left: 85%; animation-delay: 0.8s; animation-duration: 5.5s; }
    .circuit-line-5 { left: 30%; animation-delay: 3.5s; animation-duration: 7s; }

    @keyframes node-pulse {
        0%, 100% { opacity: 0.3; transform: scale(1); box-shadow: 0 0 8px rgba(34, 211, 238, 0.4); }
        50% { opacity: 0.8; transform: scale(1.2); box-shadow: 0 0 20px rgba(34, 211, 238, 0.8); }
    }
    .circuit-node {
        position: absolute;
        width: 6px;
        height: 6px;
        background: rgba(34, 211, 238, 0.9);
        border-radius: 50%;
        animation: node-pulse 3s ease-in-out infinite;
    }
    .circuit-node-1 { top: 20%; left: 25%; animation-delay: 0s; }
    .circuit-node-2 { top: 45%; left: 70%; animation-delay: 0.5s; }
    .circuit-node-3 { top: 70%; left: 15%; animation-delay: 1s; }
    .circuit-node-4 { top: 35%; left: 85%; animation-delay: 1.5s; }
    .circuit-node-5 { top: 80%; left: 55%; animation-delay: 2s; }
</style>
</x-guest-layout>
