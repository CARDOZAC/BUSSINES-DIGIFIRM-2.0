<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_alterno', 50);
            $table->string('nombre');
            $table->string('nit_rut', 50)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->string('pais', 100)->default('Colombia');
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['empresa_id', 'codigo_alterno']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
