<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('tipos_documentos');
        Schema::create('tipos_documentos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->nullable();
            $table->string('nombre_corto')->unique();
            $table->string('nombre')->unique();
            $table->enum('estado',['ACTIVO','INACTIVO'])->default('ACTIVO');
            $table->timestamps();
        });

        // Insert default values
        DB::table('tipos_documentos')->insert([
            ['codigo' => 'NIT', 'nombre_corto' => 'NIT', 'nombre' => 'Número de Identificación Tributaria','estado'=>'ACTIVO'],
            ['codigo' => 'CC', 'nombre_corto' => 'Cédula', 'nombre' => 'Cedúla de Ciudadanía','estado'=>'ACTIVO'],
            ['codigo' => 'PP', 'nombre_corto' => 'Pasaporte', 'nombre' => 'Pasaporte','estado'=>'ACTIVO'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_documentos');
    }
};
