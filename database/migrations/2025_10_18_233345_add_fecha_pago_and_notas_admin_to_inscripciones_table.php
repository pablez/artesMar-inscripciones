<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            if (!Schema::hasColumn('inscripciones', 'fecha_pago')) {
                $table->timestamp('fecha_pago')->nullable()->after('estado_pago');
            }
            if (!Schema::hasColumn('inscripciones', 'notas_admin')) {
                $table->text('notas_admin')->nullable()->after('fecha_pago');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            if (Schema::hasColumn('inscripciones', 'notas_admin')) {
                $table->dropColumn('notas_admin');
            }
            if (Schema::hasColumn('inscripciones', 'fecha_pago')) {
                $table->dropColumn('fecha_pago');
            }
        });
    }
};
