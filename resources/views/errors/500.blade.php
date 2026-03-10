<!DOCTYPE html>
<html lang="es" data-theme="rvtheme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>500 - Error del Servidor | Digital Clientes R&V</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-base-200">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full text-center">
            <h1 class="text-9xl font-extrabold text-error opacity-20">500</h1>
            <div class="card bg-base-100 shadow-2xl -mt-20">
                <div class="card-body items-center text-center">
                    <h2 class="card-title text-3xl font-bold">Error del Servidor</h2>
                    <p class="text-base-content/70 mt-2">
                        Hubo un problema inesperado. Nuestro equipo técnico ha sido notificado.
                    </p>
                    <div class="card-actions mt-6">
                        <a href="{{ url('/') }}" class="btn btn-outline btn-wide">
                            Reintentar Inicio
                        </a>
                    </div>
                </div>
            </div>
            <p class="mt-8 text-xs opacity-50 uppercase tracking-widest">Digital Clientes R&V</p>
        </div>
    </div>
</body>
</html>
