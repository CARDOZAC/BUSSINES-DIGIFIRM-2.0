<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmpresaAccess
{
    /**
     * Bloquea acceso cross-company cuando la request incluye empresa_id.
     * Usuarios admin-cartera y super_admin pueden acceder a cualquier empresa.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->hasAnyRole(['admin-cartera', 'super_admin'])) {
            return $next($request);
        }

        $empresaId = $request->route('empresa_id')
            ?? $request->input('empresa_id')
            ?? $request->query('empresa_id');

        if ($empresaId !== null && (int) $empresaId !== (int) $user->empresa_id) {
            abort(403, 'No tiene permiso para acceder a datos de esta empresa.');
        }

        return $next($request);
    }
}
