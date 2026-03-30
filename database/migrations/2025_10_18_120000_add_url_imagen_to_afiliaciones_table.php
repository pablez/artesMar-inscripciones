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
        Schema::table('afiliaciones', function (Blueprint $table) {
            if (!Schema::hasColumn('afiliaciones', 'url')) {
                $table->string('url')->nullable()->after('titulo');
            }
            if (!Schema::hasColumn('afiliaciones', 'imagen')) {
                $table->string('imagen')->nullable()->after('url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('afiliaciones', function (Blueprint $table) {
            if (Schema::hasColumn('afiliaciones', 'imagen')) {
                $table->dropColumn('imagen');
            }
            if (Schema::hasColumn('afiliaciones', 'url')) {
                $table->dropColumn('url');
            }
        });
    }
};
