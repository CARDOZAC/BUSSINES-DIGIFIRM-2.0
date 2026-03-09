<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClientePdfController extends Controller
{
    public function ver(int $id)
    {
        $cliente = Cliente::findOrFail($id);

        $this->authorize('view', $cliente);

        if (!$cliente->pdf_url) {
            abort(404, 'No hay PDF disponible para este cliente.');
        }

        return redirect($cliente->pdf_url);
    }

    public function descargar(Request $request, int $id)
    {
        $cliente = Cliente::findOrFail($id);

        $this->authorize('view', $cliente);

        if (!$cliente->pdf_url) {
            abort(404, 'No hay PDF disponible para este cliente.');
        }

        $response = Http::timeout(30)->get($cliente->pdf_url);

        if (!$response->successful()) {
            abort(502, 'No se pudo obtener el PDF.');
        }

        $nombre = "CTA-FMT-001_{$cliente->numero_documento}_{$cliente->id}.pdf";

        return response($response->body(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nombre . '"',
            'Content-Length' => strlen($response->body()),
        ]);
    }
}
