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
        Schema::create('cuentas_contables', function (Blueprint $table) {
            $table->id();

            // Código contable (ej. 110505, 2408, 510505, etc.)
            $table->string('codigo', 20)->unique();

            // Nombre de la cuenta (ej. "Caja General", "Clientes Nacionales", etc.)
            $table->string('nombre', 150);

            // Tipo: Activo, Pasivo, Patrimonio, Ingreso, Gasto, Costo
            $table->enum('tipo', [
                'activo',
                'pasivo',
                'patrimonio',
                'ingreso',
                'gasto',
                'costo',
            ]);

            // Naturaleza contable (Débito o Crédito)
            $table->enum('naturaleza', ['debito', 'credito']);

            // Nivel jerárquico (1=clase, 2=grupo, 3=cuenta, 4=subcuenta)
            $table->unsignedTinyInteger('nivel')->default(4);

            // ID de la cuenta padre (para jerarquía)
            $table->foreignId('padre_id')
                ->nullable()
                ->constrained('cuentas_contables')
                ->cascadeOnDelete();

            // Estado (activa o inactiva)
            $table->boolean('activa')->default(true);

            // Descripción opcional
            $table->text('descripcion')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentas_contables');
    }
};
