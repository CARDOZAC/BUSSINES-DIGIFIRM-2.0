<?php

namespace App\Services;

use App\Models\Cliente;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;

class PdfClienteService
{
    private CloudinaryService $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    public function generarPdf(Cliente $cliente): string
    {
        $cliente->load(['empresa', 'vendedor']);

        $qrBase64 = $this->generarQrBase64($cliente);
        $hashVerificacion = $this->generarHash($cliente);

        // Watermark selection logic
        $empresaNombre = strtolower($cliente->empresa->nombre ?? '');
        $watermarkPath = public_path('images/ajar.png'); // default

        if (str_contains($empresaNombre, 'rinval')) {
            $watermarkPath = public_path('images/rinval.png');
        } elseif (str_contains($empresaNombre, 'distmasivos')) {
            $watermarkPath = public_path('images/distmasivos.png');
        }

        $html = View::make('pdf.formato-cta-fmt-001', [
            'cliente' => $cliente,
            'empresa' => $cliente->empresa,
            'vendedor' => $cliente->vendedor,
            'qrBase64' => $qrBase64,
            'hashVerificacion' => $hashVerificacion,
            'watermark_path' => $watermarkPath,
        ])->render();

        $mpdf = new Mpdf([
            'format' => 'Letter',
            'margin_left' => 8,
            'margin_right' => 8,
            'margin_top' => 8,
            'margin_bottom' => 8,
            'default_font' => 'Arial',
            'default_font_size' => 9,
        ]);

        $mpdf->SetTitle('CTA-FMT-001 - Creación o Actualización de Cliente - ' . $cliente->nombre_razon_social);
        $mpdf->SetAuthor($cliente->empresa->nombre ?? 'Sistema de Creación de Clientes');
        $mpdf->SetCreator('DIGIFIRM 2.0 - mPDF');
        $mpdf->SetSubject('Formato CTA-FMT-001 - Creación o Actualización de Cliente');
        $mpdf->SetKeywords('CTA-FMT-001, cliente, creación, actualización, ' . $cliente->numero_documento);

        $mpdf->SetProtection(['print', 'print-highres'], '', null, 128);

        $mpdf->WriteHTML($html);

        $nombreArchivo = "CTA-FMT-001_{$cliente->numero_documento}_{$cliente->id}.pdf";
        $rutaLocal = storage_path("app/private/pdfs/{$nombreArchivo}");

        $directorio = dirname($rutaLocal);
        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $mpdf->Output($rutaLocal, \Mpdf\Output\Destination::FILE);

        return $rutaLocal;
    }

    /**
     * Genera el PDF y retorna el contenido como string (evita errores de archivo en Windows).
     */
    public function generarPdfComoString(Cliente $cliente): string
    {
        $cliente->load(['empresa', 'vendedor']);

        $qrBase64 = $this->generarQrBase64($cliente);
        $hashVerificacion = $this->generarHash($cliente);

        // Watermark selection logic
        $empresaNombre = strtolower($cliente->empresa->nombre ?? '');
        $watermarkPath = public_path('images/ajar.png'); // default

        if (str_contains($empresaNombre, 'rinval')) {
            $watermarkPath = public_path('images/rinval.png');
        } elseif (str_contains($empresaNombre, 'distmasivos')) {
            $watermarkPath = public_path('images/distmasivos.png');
        }

        $html = View::make('pdf.formato-cta-fmt-001', [
            'cliente' => $cliente,
            'empresa' => $cliente->empresa,
            'vendedor' => $cliente->vendedor,
            'qrBase64' => $qrBase64,
            'hashVerificacion' => $hashVerificacion,
            'watermark_path' => $watermarkPath,
        ])->render();

        $mpdf = new Mpdf([
            'format' => 'Letter',
            'margin_left' => 8,
            'margin_right' => 8,
            'margin_top' => 8,
            'margin_bottom' => 8,
            'default_font' => 'Arial',
            'default_font_size' => 9,
        ]);

        $mpdf->SetTitle('CTA-FMT-001 - Creación o Actualización de Cliente - ' . $cliente->nombre_razon_social);
        $mpdf->SetAuthor($cliente->empresa->nombre ?? 'Sistema de Creación de Clientes');
        $mpdf->SetCreator('DIGIFIRM 2.0 - mPDF');
        $mpdf->SetSubject('Formato CTA-FMT-001 - Creación o Actualización de Cliente');
        $mpdf->SetKeywords('CTA-FMT-001, cliente, creación, actualización, ' . $cliente->numero_documento);

        $mpdf->SetProtection(['print', 'print-highres'], '', null, 128);

        $mpdf->WriteHTML($html);

        return $mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);
    }

    public function generarYSubir(Cliente $cliente): string
    {
        $rutaLocal = $this->generarPdf($cliente);

        $resultado = $this->cloudinaryService->subirPdf(
            $rutaLocal,
            "clientes/pdfs/{$cliente->empresa->nombre}"
        );

        $cliente->update(['pdf_url' => $resultado['url']]);

        if (file_exists($rutaLocal)) {
            unlink($rutaLocal);
        }

        return $resultado['url'];
    }

    private function generarQrBase64(Cliente $cliente): string
    {
        $urlVerificacion = url("/verificar/{$cliente->codigo_verificacion_qr}");

        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'scale' => 5,
            'imageBase64' => true,
            'quietzoneSize' => 1,
        ]);

        $qrcode = new QRCode($options);

        return $qrcode->render($urlVerificacion);
    }

    private function generarHash(Cliente $cliente): string
    {
        $datos = implode('|', [
            $cliente->id,
            $cliente->numero_documento,
            $cliente->nombre_razon_social,
            $cliente->firma_base64 ? substr($cliente->firma_base64, 0, 100) : '',
            $cliente->created_at?->toIso8601String() ?? '',
            $cliente->ip_dispositivo ?? '',
        ]);

        $hashCompleto = hash('sha256', $datos);

        return substr($hashCompleto, 0, 16) . '...';
    }
}
