<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('codigo_vendedor', 20)->nullable()->after('empresa_id');
            $table->string('zona', 100)->nullable()->after('celular');
            $table->timestamp('ultimo_login')->nullable()->after('active');
            $table->string('ip_ultimo_login', 45)->nullable()->after('ultimo_login');
            $table->string('ubicacion_ultimo_login', 200)->nullable()->after('ip_ultimo_login');

            $table->unique('codigo_vendedor');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['codigo_vendedor']);
            $table->dropColumn(['codigo_vendedor', 'zona', 'ultimo_login', 'ip_ultimo_login', 'ubicacion_ultimo_login']);
        });
    }
};
