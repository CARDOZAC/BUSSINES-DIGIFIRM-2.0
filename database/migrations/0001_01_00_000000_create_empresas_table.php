<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table): void {
            $table->id();
            $table->string('nombre', 100);
            $table->string('razon_social', 200);
            $table->string('nit', 20)->unique();
            $table->string('direccion', 200)->nullable();
            $table->string('correo', 150)->nullable();
            $table->string('celular', 20)->nullable();
            $table->string('logo_path', 500)->nullable();
            $table->string('color_hex', 7)->default('#1e3a5f');
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
