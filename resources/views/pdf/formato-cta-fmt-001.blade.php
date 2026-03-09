<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 8.5pt; color: #000; }

        .page { width: 100%; }

        table.main-table {
            width: 100%;
            border-collapse: collapse;
            border: 1.5px solid #000;
        }
        table.main-table td, table.main-table th {
            border: 1px solid #000;
            padding: 3px 5px;
            vertical-align: middle;
        }

        .header-row td { border-bottom: 1.5px solid #000; }

        .section-title {
            background-color: #d9e2f3;
            font-weight: bold;
            font-size: 8.5pt;
            text-align: center;
            padding: 4px;
        }

        .field-label { font-weight: bold; font-size: 8pt; }
        .field-value { font-size: 8.5pt; min-height: 14px; }

        .checkbox {
            display: inline-block;
            width: 12px; height: 12px;
            border: 1px solid #000;
            text-align: center;
            font-size: 9pt;
            line-height: 12px;
            vertical-align: middle;
            margin-right: 2px;
        }
        .checkbox.checked { background-color: #1e3a5f; color: #fff; font-weight: bold; }

        .underline-field {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 120px;
            padding: 0 4px;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .text-bold { font-weight: bold; }
        .text-small { font-size: 7pt; }
        .text-xs { font-size: 6.5pt; }
        .no-border { border: none !important; }
        .no-border td { border: none !important; }

        .firma-img { max-width: 200px; max-height: 80px; }
        .qr-img { width: 70px; height: 70px; }

        .footer-trazabilidad {
            margin-top: 8px;
            border-top: 1px dashed #999;
            padding-top: 4px;
            font-size: 6.5pt;
            color: #555;
        }

        .legal-text { font-size: 7.5pt; text-align: justify; line-height: 1.4; }
        .consent-text { font-size: 7.5pt; text-align: justify; line-height: 1.5; }
    </style>
</head>
<body>

@php
    $logoAjar = str_replace('\\', '/', base_path('imagen/ajar.png.png'));
    $logoRinval = str_replace('\\', '/', base_path('imagen/rinval.png.jfif'));
    $logoDistmasivos = str_replace('\\', '/', base_path('imagen/distmasivos.png.jfif'));
@endphp

{{-- ===================== PÁGINA 1 ===================== --}}
<div class="page">
<table class="main-table">
    {{-- HEADER: 3 logos grupo R&V (margen izquierdo superior) --}}
    <tr class="header-row">
        <td rowspan="4" style="width: 20%; text-align: center; vertical-align: top; border-right: 1.5px solid #000; padding: 6px 4px;">
            <table style="width: 100%; border: none; margin: 0 auto;" class="no-border">
                <tr>
                    <td style="text-align: center; padding: 3px; border: none; width: 33%;">
                        @if(file_exists($logoAjar))
                        <img src="{{ $logoAjar }}" style="max-height: 36px; max-width: 60px; object-fit: contain;" alt="AJAR">
                        @endif
                    </td>
                    <td style="text-align: center; padding: 3px; border: none; width: 33%;">
                        @if(file_exists($logoRinval))
                        <img src="{{ $logoRinval }}" style="max-height: 36px; max-width: 60px; object-fit: contain;" alt="RINVAL">
                        @endif
                    </td>
                    <td style="text-align: center; padding: 3px; border: none; width: 34%;">
                        @if(file_exists($logoDistmasivos))
                        <img src="{{ $logoDistmasivos }}" style="max-height: 36px; max-width: 60px; object-fit: contain;" alt="DISTMASIVOS">
                        @endif
                    </td>
                </tr>
            </table>
            <span class="text-small" style="display: block; margin-top: 4px;">{{ $empresa->direccion ?? '' }}</span>
            <span class="text-small">{{ $empresa->correo ? 'Correo. ' . $empresa->correo : '' }}</span><br>
            <span class="text-small">{{ $empresa->celular ? 'Celular. ' . $empresa->celular : '' }}</span>
        </td>
        <td rowspan="4" style="width: 50%; text-align: center; vertical-align: middle; border-right: 1.5px solid #000;">
            <span style="font-size: 12pt; font-weight: bold;">CREACION O ACTUALIZACION DE<br>CLIENTE</span>
        </td>
        <td style="width: 15%;" class="field-label">Fecha:</td>
        <td style="width: 15%;">23/10/2024</td>
    </tr>
    <tr class="header-row">
        <td class="field-label">Versión:</td>
        <td>002</td>
    </tr>
    <tr class="header-row">
        <td class="field-label">Código:</td>
        <td>CTA-FMT-001</td>
    </tr>
    <tr class="header-row">
        <td class="field-label">Página</td>
        <td>1 de 2</td>
    </tr>

    {{-- ASESOR Y ZONA --}}
    <tr>
        <td colspan="2" style="border-right: 1.5px solid #000;">
            <span class="field-label">NOMBRE ASESOR COMERCIAL:</span>
            <span class="field-value">{{ $vendedor->name ?? '' }}</span>
        </td>
        <td colspan="2">
            <span class="field-label">ZONA:</span>
            <span class="field-value">{{ $cliente->zona ?? '' }}</span>
        </td>
    </tr>

    {{-- TIPO DE SOLICITUD --}}
    <tr>
        <td colspan="4" class="section-title">TIPO DE SOLICITUD</td>
    </tr>
    <tr>
        <td colspan="3" style="border-right: 1.5px solid #000;">
            <span class="checkbox {{ $cliente->tipo_solicitud === 'creacion' ? 'checked' : '' }}">{{ $cliente->tipo_solicitud === 'creacion' ? '✓' : '' }}</span>
            CREACION CLIENTE CONTADO
            &nbsp;&nbsp;&nbsp;
            <span class="checkbox {{ $cliente->tipo_solicitud === 'actualizacion' ? 'checked' : '' }}">{{ $cliente->tipo_solicitud === 'actualizacion' ? '✓' : '' }}</span>
            ACTUALIZACION DATOS
            &nbsp;&nbsp;&nbsp;
            <span class="checkbox {{ $cliente->tipo_solicitud === 'reactivacion' ? 'checked' : '' }}">{{ $cliente->tipo_solicitud === 'reactivacion' ? '✓' : '' }}</span>
            REACTIVACION CLIENTE CONTADO
        </td>
        <td>
            <span class="field-label">FECHA DILIGENCIAMIENTO</span><br>
            @php
                $fecha = $cliente->fecha_diligenciamiento;
                $dia = $fecha ? $fecha->format('d') : '';
                $mes = $fecha ? $fecha->format('m') : '';
                $anio = $fecha ? $fecha->format('Y') : '';
            @endphp
            <span class="text-small">D</span> <span class="underline-field" style="min-width: 20px;">{{ $dia }}</span>
            <span class="text-small">M</span> <span class="underline-field" style="min-width: 20px;">{{ $mes }}</span>
            <span class="text-small">A</span> <span class="underline-field" style="min-width: 30px;">{{ $anio }}</span>
        </td>
    </tr>

    {{-- INFORMACIÓN GENERAL --}}
    <tr>
        <td colspan="4" class="section-title">INFORMACIÓN GENERAL</td>
    </tr>
    <tr>
        <td colspan="4">
            <span class="field-label">Tipo de documento:</span>
            <span class="checkbox {{ $cliente->tipo_documento === 'CC' ? 'checked' : '' }}">{{ $cliente->tipo_documento === 'CC' ? '✓' : '' }}</span> CC
            &nbsp;
            <span class="checkbox {{ $cliente->tipo_documento === 'NIT' ? 'checked' : '' }}">{{ $cliente->tipo_documento === 'NIT' ? '✓' : '' }}</span> NIT
            &nbsp;
            <span class="checkbox {{ $cliente->tipo_documento === 'CE' ? 'checked' : '' }}">{{ $cliente->tipo_documento === 'CE' ? '✓' : '' }}</span> C.E
            &nbsp;&nbsp;
            <span class="field-label">No.</span>
            <span class="underline-field" style="min-width: 200px;">{{ $cliente->numero_documento }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <span class="field-label">Nombre o razón social:</span>
            <span class="underline-field" style="min-width: 80%;">{{ $cliente->nombre_razon_social }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <span class="field-label">Nombre del establecimiento:</span>
            <span class="underline-field" style="min-width: 75%;">{{ $cliente->nombre_establecimiento ?? '' }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <span class="field-label">Correo electrónico:</span>
            <span class="underline-field" style="min-width: 80%;">{{ $cliente->correo_electronico ?? '' }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="border-right: 1.5px solid #000;">
            <span class="field-label">Dirección:</span>
            <span class="underline-field" style="min-width: 70%;">{{ $cliente->direccion }}</span>
        </td>
        <td>
            <span class="field-label">Barrio:</span>
            <span class="underline-field">{{ $cliente->barrio ?? '' }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="border-right: 1.5px solid #000;">
            <span class="field-label">Ciudad / Depto:</span>
            <span class="underline-field" style="min-width: 65%;">{{ $cliente->ciudad_departamento }}</span>
        </td>
        <td>
            <span class="field-label">No. Celular</span>
            <span class="underline-field">{{ $cliente->celular }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <span class="field-label">Tipo de negocio:</span>
            <span class="checkbox {{ $cliente->tipo_negocio === 'mayorista' ? 'checked' : '' }}">{{ $cliente->tipo_negocio === 'mayorista' ? '✓' : '' }}</span> Mayorista
            &nbsp;
            <span class="checkbox {{ $cliente->tipo_negocio === 'supermercado' ? 'checked' : '' }}">{{ $cliente->tipo_negocio === 'supermercado' ? '✓' : '' }}</span> Supermercado
            &nbsp;
            <span class="checkbox {{ $cliente->tipo_negocio === 'tienda' ? 'checked' : '' }}">{{ $cliente->tipo_negocio === 'tienda' ? '✓' : '' }}</span> Tienda
            &nbsp;
            <span class="field-label">Otro:</span>
            <span class="underline-field">{{ $cliente->tipo_negocio === 'otro' ? ($cliente->tipo_negocio_otro ?? '') : '' }}</span>
        </td>
    </tr>

    {{-- REPRESENTANTE LEGAL --}}
    <tr>
        <td colspan="4" class="section-title">REPRESENTANTE LEGAL - PERSONA JURIDICA</td>
    </tr>
    <tr>
        <td colspan="4">
            <span class="field-label">Nombre del representante legal:</span>
            <span class="underline-field" style="min-width: 70%;">{{ $cliente->representante_legal_nombre ?? '' }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <span class="field-label">Tipo de documento:</span>
            <span class="checkbox {{ ($cliente->representante_legal_tipo_documento ?? '') === 'CC' ? 'checked' : '' }}">{{ ($cliente->representante_legal_tipo_documento ?? '') === 'CC' ? '✓' : '' }}</span> C.C
            &nbsp;
            <span class="checkbox {{ ($cliente->representante_legal_tipo_documento ?? '') === 'NIT' ? 'checked' : '' }}">{{ ($cliente->representante_legal_tipo_documento ?? '') === 'NIT' ? '✓' : '' }}</span> NIT
            &nbsp;
            <span class="checkbox {{ ($cliente->representante_legal_tipo_documento ?? '') === 'CE' ? 'checked' : '' }}">{{ ($cliente->representante_legal_tipo_documento ?? '') === 'CE' ? '✓' : '' }}</span> CE
            &nbsp;&nbsp;
            <span class="field-label">No.</span>
            <span class="underline-field" style="min-width: 200px;">{{ $cliente->representante_legal_numero_documento ?? '' }}</span>
        </td>
    </tr>

    {{-- INFORMACIÓN TRIBUTARIA --}}
    <tr>
        <td colspan="4" class="section-title">INFORMACIÓN TRIBUTARIA</td>
    </tr>
    <tr>
        <td colspan="4">
            <span class="field-label">Código CIIU:</span>
            <span class="underline-field" style="min-width: 80px;">{{ $cliente->codigo_ciiu ?? '' }}</span>
            &nbsp;&nbsp;&nbsp;
            <span class="field-label">Actividad económica:</span>
            <span class="underline-field" style="min-width: 50%;">{{ $cliente->actividad_economica ?? '' }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <span class="field-label">Agente retención en la fuente</span>
            &nbsp;
            <span class="checkbox {{ $cliente->agente_retencion_fuente ? 'checked' : '' }}">{{ $cliente->agente_retencion_fuente ? '✓' : '' }}</span> SI
            &nbsp;
            <span class="checkbox {{ !$cliente->agente_retencion_fuente ? 'checked' : '' }}">{{ !$cliente->agente_retencion_fuente ? '✓' : '' }}</span> NO
            &nbsp;&nbsp;&nbsp;&nbsp;
            <span class="checkbox {{ $cliente->responsable_iva === 'no_responsable' ? 'checked' : '' }}">{{ $cliente->responsable_iva === 'no_responsable' ? '✓' : '' }}</span> No responsable de IVA
            &nbsp;
            <span class="checkbox {{ $cliente->responsable_iva === 'responsable' ? 'checked' : '' }}">{{ $cliente->responsable_iva === 'responsable' ? '✓' : '' }}</span> Responsable IVA
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <span class="field-label">Tipo de régimen</span>
            &nbsp;
            <span class="checkbox {{ $cliente->tipo_regimen === 'gran_contribuyente' ? 'checked' : '' }}">{{ $cliente->tipo_regimen === 'gran_contribuyente' ? '✓' : '' }}</span> Gran Contribuyente
            &nbsp;&nbsp;
            <span class="checkbox {{ $cliente->tipo_regimen === 'autorretenedor' ? 'checked' : '' }}">{{ $cliente->tipo_regimen === 'autorretenedor' ? '✓' : '' }}</span> Autorretenedor
        </td>
    </tr>
    <tr>
        <td colspan="4">
            <span class="field-label">Agente retención ICO</span>
            &nbsp;
            <span class="checkbox {{ $cliente->agente_retencion_ico ? 'checked' : '' }}">{{ $cliente->agente_retencion_ico ? '✓' : '' }}</span> SI
            &nbsp;
            <span class="checkbox {{ !$cliente->agente_retencion_ico ? 'checked' : '' }}">{{ !$cliente->agente_retencion_ico ? '✓' : '' }}</span> NO
        </td>
    </tr>

    {{-- CORREO FACTURA ELECTRÓNICA --}}
    <tr>
        <td colspan="4" class="section-title">CORREO FACTURA ELECTRÓNICA</td>
    </tr>
    <tr>
        <td colspan="4" class="legal-text">
            Con este documento, autorizo que todos los documentos electrónicos emitidos a mi nombre sean enviados a la siguiente dirección de correo electrónico:
            <span class="underline-field" style="min-width: 200px;"><strong>{{ $cliente->correo_factura_electronica ?? '' }}</strong></span>.
        </td>
    </tr>

    {{-- DECLARACIÓN ORIGEN DE FONDOS --}}
    <tr>
        <td colspan="4" class="section-title">DECLARACIÓN ORIGEN DE FONDOS</td>
    </tr>
    <tr>
        <td colspan="4" class="legal-text" style="padding: 5px 8px;">
            De manera voluntaria y dando certeza de que todo lo aquí consignado es cierto, realizo la siguiente declaración en cumplimiento con la normatividad de anticorrupción
            y SAGRILAFT (Sistema de Autocontrol y Gestión del Riesgo Integral de Lavado de Activos y Financiación del Terrorismo):
            1. Los recursos que manejo o mis recursos propios provienen de las siguientes fuentes
            <span class="underline-field" style="min-width: 150px;"><strong>{{ $cliente->fuente_recursos ?? '' }}</strong></span>.
            2. Declaro que estos recursos no provienen de ninguna actividad ilícita de las contempladas en el Código Penal Colombiano o en cualquier norma que lo modifique o adicione.
            3. No admitiré que terceros efectúen depósitos a nombre mío, con fondos provenientes de las actividades ilícitas contempladas en el Código Penal Colombiano o en
            cualquier norma que lo modifique o adicione, ni efectuaré transacciones destinadas a tales actividades o a favor de personas relacionadas con las mismas.
        </td>
    </tr>
</table>

{{-- FOOTER TRAZABILIDAD PÁG 1 --}}
<div class="footer-trazabilidad">
    ID Doc: CTA-FMT-001-{{ str_pad($cliente->id, 5, '0', STR_PAD_LEFT) }} |
    Creado: {{ $cliente->created_at->format('d/m/Y H:i:s') }} |
    Vendedor: {{ $vendedor->name ?? 'N/A' }} |
    IP: {{ $cliente->ip_dispositivo ?? 'N/A' }}
</div>
</div>

{{-- ===================== PÁGINA 2 ===================== --}}
<pagebreak />

<div class="page">
<table class="main-table">
    {{-- HEADER PÁG 2: mismos 3 logos --}}
    <tr class="header-row">
        <td rowspan="4" style="width: 20%; text-align: center; vertical-align: top; border-right: 1.5px solid #000; padding: 6px 4px;">
            <table style="width: 100%; border: none; margin: 0 auto;" class="no-border">
                <tr>
                    <td style="text-align: center; padding: 3px; border: none; width: 33%;">
                        @if(file_exists($logoAjar))
                        <img src="{{ $logoAjar }}" style="max-height: 36px; max-width: 60px; object-fit: contain;" alt="AJAR">
                        @endif
                    </td>
                    <td style="text-align: center; padding: 3px; border: none; width: 33%;">
                        @if(file_exists($logoRinval))
                        <img src="{{ $logoRinval }}" style="max-height: 36px; max-width: 60px; object-fit: contain;" alt="RINVAL">
                        @endif
                    </td>
                    <td style="text-align: center; padding: 3px; border: none; width: 34%;">
                        @if(file_exists($logoDistmasivos))
                        <img src="{{ $logoDistmasivos }}" style="max-height: 36px; max-width: 60px; object-fit: contain;" alt="DISTMASIVOS">
                        @endif
                    </td>
                </tr>
            </table>
            <span class="text-small" style="display: block; margin-top: 4px;">{{ $empresa->direccion ?? '' }}</span>
            <span class="text-small">{{ $empresa->correo ? 'Correo. ' . $empresa->correo : '' }}</span><br>
            <span class="text-small">{{ $empresa->celular ? 'Celular. ' . $empresa->celular : '' }}</span>
        </td>
        <td rowspan="4" style="width: 50%; text-align: center; vertical-align: middle; border-right: 1.5px solid #000;">
            <span style="font-size: 12pt; font-weight: bold;">CREACION O ACTUALIZACION DE<br>CLIENTE</span>
        </td>
        <td style="width: 15%;" class="field-label">Fecha:</td>
        <td style="width: 15%;">23/10/2024</td>
    </tr>
    <tr class="header-row">
        <td class="field-label">Versión:</td>
        <td>002</td>
    </tr>
    <tr class="header-row">
        <td class="field-label">Código:</td>
        <td>CTA-FMT-001</td>
    </tr>
    <tr class="header-row">
        <td class="field-label">Página</td>
        <td>2 de 2</td>
    </tr>

    {{-- CONSENTIMIENTO TRATAMIENTO DE DATOS PERSONALES --}}
    <tr>
        <td colspan="4" class="section-title">CONSENTIMIENTO PARA TRATAMIENTO DE DATOS PERSONALES</td>
    </tr>
    <tr>
        <td colspan="4" class="consent-text" style="padding: 6px 8px;">
            De acuerdo con la Ley Estatutaria 1581 de 2012 de protección de datos y sus normas reglamentarias, mediante la firma de este
            documento, yo <strong class="underline-field">{{ $cliente->nombre_razon_social }}</strong>,
            identificado(a) con documento <strong class="underline-field">{{ $cliente->tipo_documento }}</strong>
            y número <strong class="underline-field">{{ $cliente->numero_documento }}</strong>,
            actuando en nombre propio
            @if($cliente->esPersonaJuridica())
                y en representación de <strong class="underline-field">{{ $cliente->nombre_razon_social }}</strong>
                con Nit. <strong class="underline-field">{{ $cliente->numero_documento }}</strong>
            @endif
            , autorizo de manera voluntaria,
            previa, expresa, e informada, a <strong>{{ $empresa->razon_social }}</strong>, para que realice el tratamiento de los datos personales aquí
            consignados, de conformidad con su política de tratamiento de datos personales, para todos los fines requeridos para el
            cumplimiento de nuestra relación contractual, comercial, de atención al cliente, mercadeo, procesamiento, investigación,
            capacitación, acreditación, consolidación, actualización, reporte, estadística, encuestas, atención y tramitación, para el
            cumplimiento de los requisitos legales que sean aplicables de conformidad con la normatividad vigente y de igual forma, con
            la firma de este documento manifiesto que se me ha informado lo siguiente:
            1). que <strong>{{ $empresa->razon_social }}</strong>, será el
            responsable del tratamiento de los datos personales aquí consignados y que podrá recolectar, usar y tratar mis datos conforme
            a la política;
            2). que es de carácter facultativo responder preguntas o suministrar información relacionada con datos sensibles
            o menores de edad;
            3). que en mi condición de titular de los datos tengo los derechos previstos en la Constitución y en la Ley
            1581 de 2012, especialmente el derecho a conocer, actualizar, rectificar y suprimir mi información personal, así como el
            derecho a revocar el consentimiento para el tratamiento de mis datos personales;
            4). que <strong>{{ $empresa->razon_social }}</strong> garantiza
            que el tratamiento de mis datos se realizará cumpliendo los principios de confidencialidad, libertad, seguridad, veracidad,
            transparencia, acceso y circulación restringida;
            5). que <strong>{{ $empresa->razon_social }}</strong>, ha dispuesto los canales y medios que se
            indican en la política, para el ejercicio de los derechos que tengo como titular de los datos;
            6). que cualquier solicitud o
            petición que esté relacionada con el tratamiento de mis datos personales la podré realizar al siguiente correo electrónico:
            <strong>{{ $empresa->correo ?? 'N/A' }}</strong>
        </td>
    </tr>

    {{-- ESPACIO PARA FIRMAR --}}
    <tr>
        <td colspan="4" class="section-title">ESPACIO PARA FIRMAR (cliente)</td>
    </tr>
    <tr>
        <td colspan="4" style="padding: 10px; min-height: 120px;">
            <table style="width: 100%; border: none;" class="no-border">
                <tr>
                    <td style="width: 60%; vertical-align: top; padding: 5px;">
                        <strong>Firma y huella Rep. Legal y/o persona natural</strong><br><br>
                        @if($cliente->firma_base64)
                            <img src="{{ $cliente->firma_base64 }}" class="firma-img" alt="Firma digital"><br>
                        @else
                            <div style="border-bottom: 1px solid #000; width: 200px; height: 60px;"></div>
                        @endif
                        <br>
                        <span class="field-label">Nombre:</span> <span class="underline-field" style="min-width: 200px;">{{ $cliente->nombre_razon_social }}</span><br>
                        <span class="field-label">C.C.</span> <span class="underline-field" style="min-width: 200px;">{{ $cliente->tipo_documento !== 'NIT' ? $cliente->numero_documento : ($cliente->representante_legal_numero_documento ?? '') }}</span><br>
                        <span class="field-label">Razón social:</span> <span class="underline-field" style="min-width: 180px;">{{ $cliente->esPersonaJuridica() ? $cliente->nombre_razon_social : '' }}</span><br>
                        <span class="field-label">Nit:</span> <span class="underline-field" style="min-width: 200px;">{{ $cliente->esPersonaJuridica() ? $cliente->numero_documento : '' }}</span>
                    </td>
                    <td style="width: 40%; vertical-align: top; text-align: center; padding: 5px;">
                        @if($qrBase64 ?? false)
                            <img src="{{ $qrBase64 }}" class="qr-img" alt="QR Verificación"><br>
                            <span class="text-xs">Escanear para verificar</span>
                        @endif
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    {{-- USO EXCLUSIVO INTERNO --}}
    <tr>
        <td colspan="4" class="section-title">USO EXCLUSIVO INTERNO</td>
    </tr>
    <tr>
        <td colspan="4" style="padding: 5px 8px;">
            <strong>Clientes de contado:</strong>
            &nbsp;
            <span class="checkbox {{ $cliente->cliente_contado ? 'checked' : '' }}">{{ $cliente->cliente_contado ? '✓' : '' }}</span> Si
            &nbsp;
            <span class="checkbox {{ !$cliente->cliente_contado ? 'checked' : '' }}">{{ !$cliente->cliente_contado ? '✓' : '' }}</span> No
        </td>
    </tr>
    <tr>
        <td colspan="4" style="padding: 5px 8px;">
            1. Formato diligenciado (CTA-FMT-001).
            &nbsp;
            <span class="checkbox checked">✓</span>
            <br>
            2. Documento de identidad al 150% (requisito para creación).
            &nbsp;
            <span class="checkbox {{ ($cliente->checklist_documento_identidad ?? false) ? 'checked' : '' }}">{{ ($cliente->checklist_documento_identidad ?? false) ? '✓' : '' }}</span>
            <br>
            3. Copia RUT.
            &nbsp;
            <span class="checkbox {{ ($cliente->checklist_rut ?? false) ? 'checked' : '' }}">{{ ($cliente->checklist_rut ?? false) ? '✓' : '' }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="4" class="text-center text-bold text-small" style="padding: 4px; background-color: #f0f0f0;">
            * ESTA INFORMACIÓN ES USO EXCLUSIVO CREACIÓN CLIENTE *
        </td>
    </tr>
</table>

{{-- FOOTER TRAZABILIDAD PÁG 2 --}}
<div class="footer-trazabilidad">
    <table style="width: 100%; border: none; font-size: 6.5pt; color: #555;">
        <tr>
            <td style="width: 75%; border: none; vertical-align: top; padding: 0;">
                <strong>Creado por:</strong> {{ $vendedor->name ?? 'N/A' }}<br>
                <strong>Fecha/Hora:</strong> {{ $cliente->created_at->format('d/m/Y H:i:s') }} | <strong>IP:</strong> {{ $cliente->ip_dispositivo ?? 'N/A' }}<br>
                <strong>ID Doc:</strong> CTA-FMT-001-{{ str_pad($cliente->id, 5, '0', STR_PAD_LEFT) }}<br>
                <strong>Hash:</strong> {{ $hashVerificacion ?? 'N/A' }}<br>
                <strong>User-Agent:</strong> {{ Str::limit($cliente->user_agent_dispositivo ?? 'N/A', 80) }}
            </td>
            <td style="width: 25%; border: none; text-align: right; vertical-align: top; padding: 0;">
                @if($qrBase64 ?? false)
                    <img src="{{ $qrBase64 }}" style="width: 55px; height: 55px;" alt="QR">
                @endif
            </td>
        </tr>
    </table>
</div>
</div>

</body>
</html>
