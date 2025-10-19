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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nit', 30)->unique()->nullable(false);
            $table->string('razon_social', 150)->unique()->nullable(false);
            $table->string('siglas', 150)->nullable();
            $table->string('nombre_representante_legal', 150)->nullable();
            $table->string('nombre_contador', 150)->nullable();
            $table->string('matricula_contador', 150)->nullable();
            $table->string('nombre_revisor_fiscal', 150)->nullable();
            $table->string('matricula_revisor_fiscal', 150)->nullable();
            $table->text('url_logo')->nullable();
            $table->string('estado', 150)->default('ACTIVO');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
