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
        Schema::table('horarios', function (Blueprint $table) {
            if (!Schema::hasColumn('horarios', 'disciplina_id')) {
                $table->unsignedBigInteger('disciplina_id')->nullable()->after('titulo');
            }
            if (!Schema::hasColumn('horarios', 'categoria')) {
                $table->string('categoria')->nullable()->after('disciplina_id');
            }
            if (!Schema::hasColumn('horarios', 'sucursal')) {
                $table->string('sucursal')->nullable()->after('hora');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('horarios', function (Blueprint $table) {
            if (Schema::hasColumn('horarios', 'sucursal')) {
                $table->dropColumn('sucursal');
            }
            if (Schema::hasColumn('horarios', 'categoria')) {
                $table->dropColumn('categoria');
            }
            if (Schema::hasColumn('horarios', 'disciplina_id')) {
                $table->dropColumn('disciplina_id');
            }
        });
    }
};
