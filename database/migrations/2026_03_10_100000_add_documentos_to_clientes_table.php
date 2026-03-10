<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->boolean('persona_natural_no_responsable_iva')->default(false)->after('representante_legal_numero_documento');
            $table->string('cedula_pdf_url')->nullable()->after('foto_url');
            $table->string('rut_pdf_url')->nullable()->after('cedula_pdf_url');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['persona_natural_no_responsable_iva', 'cedula_pdf_url', 'rut_pdf_url']);
        });
    }
};
