<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table): void {
            $table->foreignId('empresa_id')->nullable()->after('user_id')->constrained('empresas')->nullOnDelete();
            $table->string('modulo', 50)->nullable()->after('accion');
            $table->decimal('latitud', 10, 7)->nullable()->after('user_agent');
            $table->decimal('longitud', 10, 7)->nullable()->after('latitud');
            $table->string('ciudad', 100)->nullable()->after('longitud');
            $table->string('pais', 100)->nullable()->after('ciudad');

            $table->index('empresa_id');
            $table->index('modulo');
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table): void {
            $table->dropForeign(['empresa_id']);
            $table->dropIndex(['empresa_id']);
            $table->dropIndex(['modulo']);
            $table->dropColumn(['empresa_id', 'modulo', 'latitud', 'longitud', 'ciudad', 'pais']);
        });
    }
};
