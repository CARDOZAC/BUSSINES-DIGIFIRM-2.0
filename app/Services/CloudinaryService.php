<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    public function subirFotoCliente(UploadedFile $archivo, string $carpeta = 'clientes/fotos'): array
    {
        $resultado = Cloudinary::upload($archivo->getRealPath(), [
            'folder' => $carpeta,
            'transformation' => [
                'width' => 800,
                'height' => 800,
                'crop' => 'limit',
                'quality' => 'auto:good',
            ],
        ]);

        return [
            'url' => $resultado->getSecurePath(),
            'public_id' => $resultado->getPublicId(),
        ];
    }

    public function subirPdf(string $rutaLocal, string $carpeta = 'clientes/pdfs'): array
    {
        $resultado = Cloudinary::upload($rutaLocal, [
            'folder' => $carpeta,
            'resource_type' => 'raw',
        ]);

        return [
            'url' => $resultado->getSecurePath(),
            'public_id' => $resultado->getPublicId(),
        ];
    }

    public function subirPdfArchivo(UploadedFile $archivo, string $carpeta = 'clientes/documentos'): array
    {
        $resultado = Cloudinary::upload($archivo->getRealPath(), [
            'folder' => $carpeta,
            'resource_type' => 'raw',
        ]);

        return [
            'url' => $resultado->getSecurePath(),
            'public_id' => $resultado->getPublicId(),
        ];
    }

    public function eliminar(string $publicId, string $resourceType = 'image'): void
    {
        Cloudinary::destroy($publicId, ['resource_type' => $resourceType]);
    }
}
