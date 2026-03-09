<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('vendedor_id')->constrained('users')->cascadeOnDelete();

            // --- Tipo de solicitud ---
            $table->enum('tipo_solicitud', ['creacion', 'actualizacion', 'reactivacion']);
            $table->date('fecha_diligenciamiento');
            $table->string('zona', 100)->nullable();

            // --- Información general ---
            $table->enum('tipo_documento', ['CC', 'NIT', 'CE']);
            $table->string('numero_documento', 30);
            $table->string('nombre_razon_social', 250);
            $table->string('nombre_establecimiento', 250)->nullable();
            $table->string('correo_electronico', 200)->nullable();
            $table->string('direccion', 300);
            $table->string('barrio', 150)->nullable();
            $table->string('ciudad_departamento', 150);
            $table->string('celular', 20);
            $table->enum('tipo_negocio', ['mayorista', 'supermercado', 'tienda', 'otro']);
            $table->string('tipo_negocio_otro', 100)->nullable();

            // --- Representante legal (persona jurídica, opcional) ---
            $table->string('representante_legal_nombre', 250)->nullable();
            $table->enum('representante_legal_tipo_documento', ['CC', 'NIT', 'CE'])->nullable();
            $table->string('representante_legal_numero_documento', 30)->nullable();

            // --- Información tributaria ---
            $table->string('codigo_ciiu', 10)->nullable();
            $table->string('actividad_economica', 200)->nullable();
            $table->boolean('agente_retencion_fuente')->default(false);
            $table->enum('responsable_iva', ['responsable', 'no_responsable'])->default('no_responsable');
            $table->enum('tipo_regimen', ['gran_contribuyente', 'autorretenedor'])->nullable();
            $table->boolean('agente_retencion_ico')->default(false);

            // --- Facturación electrónica ---
            $table->string('correo_factura_electronica', 200)->nullable();

            // --- SAGRILAFT ---
            $table->text('fuente_recursos')->nullable();

            // --- Firma y foto ---
            $table->longText('firma_base64')->nullable();
            $table->string('foto_url', 500)->nullable();

            // --- Uso interno ---
            $table->boolean('cliente_contado')->default(true);
            $table->boolean('checklist_formato')->default(false);
            $table->boolean('checklist_documento_identidad')->default(false);
            $table->boolean('checklist_rut')->default(false);

            // --- Trazabilidad ---
            $table->string('ip_dispositivo', 45)->nullable();
            $table->text('user_agent_dispositivo')->nullable();
            $table->uuid('codigo_verificacion_qr')->unique();
            $table->string('pdf_url', 500)->nullable();

            // --- Geolocalización ---
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();

            // --- Estado ---
            $table->enum('estado', ['borrador', 'completado', 'anulado'])->default('borrador');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['empresa_id', 'numero_documento']);
            $table->index(['vendedor_id', 'estado']);
            $table->index('fecha_diligenciamiento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
