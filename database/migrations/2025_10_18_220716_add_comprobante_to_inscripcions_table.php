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
            $table->string('comprobante')->nullable()->after('mensaje');
            $table->enum('estado_pago', ['pendiente', 'pendiente_verificacion', 'confirmado', 'rechazado'])
                  ->default('pendiente')->after('comprobante');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            $table->dropColumn(['comprobante', 'estado_pago']);
        });
    }
};
