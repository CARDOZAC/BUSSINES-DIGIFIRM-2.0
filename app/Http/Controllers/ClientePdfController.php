<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Services\ActivityLogService;
use App\Services\PdfClienteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ClientePdfController extends Controller
{
    public function ver(int $id)
    {
        $cliente = Cliente::findOrFail($id);

        $this->authorize('view', $cliente);

        app(ActivityLogService::class)->registrarAccion('ver_pdf', 'clientes', $cliente->id, "Visualización PDF cliente #{$cliente->id}");

        if ($cliente->pdf_url) {
            return redirect($cliente->pdf_url);
        }

        return $this->responderPdfLocal($cliente, 'inline');
    }

    public function descargar(Request $request, int $id)
    {
        $cliente = Cliente::findOrFail($id);

        $this->authorize('view', $cliente);

        app(ActivityLogService::class)->registrarAccion('descargar_pdf', 'clientes', $cliente->id, "Descarga PDF cliente #{$cliente->id}");

        if ($cliente->pdf_url) {
            $response = Http::timeout(30)->get($cliente->pdf_url);
            if ($response->successful()) {
                $nombre = "CTA-FMT-001_{$cliente->numero_documento}_{$cliente->id}.pdf";
                return response($response->body(), 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="' . $nombre . '"',
                    'Content-Length' => strlen($response->body()),
                ]);
            }
        }

        return $this->responderPdfLocal($cliente, 'attachment');
    }

    private function responderPdfLocal(Cliente $cliente, string $disposition): \Illuminate\Http\Response
    {
        try {
            $pdfService = app(PdfClienteService::class);
            $contenido = $pdfService->generarPdfComoString($cliente);
            $nombre = "CTA-FMT-001_{$cliente->numero_documento}_{$cliente->id}.pdf";

            return response($contenido, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => $disposition . '; filename="' . $nombre . '"',
                'Content-Length' => strlen($contenido),
            ]);
        } catch (\Throwable $e) {
            report($e);
            abort(500, 'No se pudo generar el PDF. Revise storage/logs/laravel.log para más detalles.');
        }
    }
}
