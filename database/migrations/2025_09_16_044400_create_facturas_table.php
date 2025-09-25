<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('dni_cliente');
            $table->string('cliente');
            $table->string('contrato');
            $table->string('periodo');
            $table->string('generada_por');
            $table->date('fecha_generada');
            $table->date('fecha_limite_pago');
            $table->decimal('valor',15,2);
            $table->string('tipo', )->default('RESIDENCIAL');
            $table->string('estado', )->default('Pendiente');
            $table->timestamps();
        });

        // Insert 100 default records
        for ($i = 1; $i <= 100; $i++) {
            DB::table('facturas')->insert([
            'uuid' => (string) Str::uuid(),
            'dni_cliente' => '12345678' . $i,
            'cliente' => 'Cliente ' . $i,
            'contrato' => 'Contrato ' . $i,
            'periodo' => '2025-09',
            'generada_por' => 'Admin',
            'fecha_generada' => now(),
            'fecha_limite_pago' => now()->addDays(30),
            'valor' => rand(1000, 25000),
            'tipo' => 'RESIDENCIAL',
            'estado' => 'Pendiente',
            'created_at' => now(),
            'updated_at' => now(),
            ]);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
