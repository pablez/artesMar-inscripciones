<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nombre');
            $table->string('telefono')->nullable();
            $table->string('email');
            $table->unsignedBigInteger('disciplina_id')->nullable();
            $table->string('categoria')->nullable();
            $table->string('dias')->nullable();
            $table->string('hora')->nullable();
            $table->string('sucursal')->nullable();
            $table->text('mensaje')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('disciplina_id')->references('id')->on('disciplinas')->onDelete('set null');
            
            // Indices
            $table->index('user_id');
            $table->index('disciplina_id');
            $table->index('email');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};
