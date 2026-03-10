<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClienteDocumentoController extends Controller
{
    public function descargar(Request $request, int $id, string $tipo)
    {
        $cliente = Cliente::findOrFail($id);
        $this->authorize('view', $cliente);

        $url = $tipo === 'cedula' ? $cliente->cedula_pdf_url : $cliente->rut_pdf_url;

        if (!$url) {
            abort(404, 'Documento no disponible.');
        }

        if (str_starts_with($url, 'local:')) {
            $path = substr($url, 6);
            $fullPath = storage_path('app/private/' . $path);
            if (!file_exists($fullPath)) {
                abort(404, 'Archivo no encontrado.');
            }
            $nombre = $tipo === 'cedula'
                ? "cedula_{$cliente->numero_documento}.pdf"
                : "rut_{$cliente->numero_documento}.pdf";

            return response()->file($fullPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $nombre . '"',
            ]);
        }

        $response = \Illuminate\Support\Facades\Http::timeout(30)->get($url);
        if (!$response->successful()) {
            abort(502, 'No se pudo obtener el documento.');
        }

        $nombre = $tipo === 'cedula'
            ? "cedula_{$cliente->numero_documento}.pdf"
            : "rut_{$cliente->numero_documento}.pdf";

        return response($response->body(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nombre . '"',
        ]);
    }
}
