<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->foreignId('proveedor_id')->nullable()->after('empresa_id')->constrained('proveedores')->onDelete('set null');
            $table->boolean('tiene_codigo_unico')->default(false)->after('proveedor_id');
            $table->string('codigo_unico_registro', 100)->nullable()->after('tiene_codigo_unico');
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign(['proveedor_id']);
            $table->dropColumn(['tiene_codigo_unico', 'codigo_unico_registro']);
        });
    }
};
