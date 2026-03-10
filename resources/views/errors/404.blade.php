<!DOCTYPE html>
<html lang="es" data-theme="rvtheme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - No Encontrado | Digital Clientes R&V</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-base-200">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full text-center">
            <h1 class="text-9xl font-extrabold text-primary opacity-20">404</h1>
            <div class="card bg-base-100 shadow-2xl -mt-20">
                <div class="card-body items-center text-center">
                    <h2 class="card-title text-3xl font-bold">Página no encontrada</h2>
                    <p class="text-base-content/70 mt-2">
                        Lo sentimos, el recurso que buscas no está disponible en este momento.
                    </p>
                    <div class="card-actions mt-6">
                        <a href="{{ url('/') }}" class="btn btn-primary btn-wide">
                            Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
            <p class="mt-8 text-xs opacity-50 uppercase tracking-widest">Digital Clientes R&V</p>
        </div>
    </div>
</body>
</html>
