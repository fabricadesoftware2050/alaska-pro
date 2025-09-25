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
        Schema::create('facturas_servicios_publicos', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string('contrato');
            $table->string('nit_cliente');
            $table->string('suscriptor');
            $table->string('direccion')->nullable();
            $table->integer('estrato')->nullable();
            $table->string('uso')->nullable();
            $table->string('periodo');
            $table->string('desde_hasta')->nullable();
            $table->integer('dias_consumo')->nullable();
            $table->date('fecha_factura');
            $table->date('fecha_limite_pago')->nullable();

            // Totales
            $table->decimal('total_acueducto', 12, 2)->default(0);
            $table->decimal('total_alcantarillado', 12, 2)->default(0);
            $table->decimal('total_aseo', 12, 2)->default(0);
            $table->integer('consumo_total_acueducto')->default(0);
            $table->integer('consumo_basico_acueducto')->default(0);
            $table->integer('consumo_complementario_acueducto')->default(0);
            $table->integer('consumo_suntuario_acueducto')->default(0);

            // Servicios
            $table->integer('frecuencia_recoleccion')->nullable();
            $table->decimal('total_factura', 12, 2)->default(0);
            $table->integer('lectura_anterior')->nullable();
            $table->integer('lectura_actual')->nullable();

            // Subsidios
            $table->decimal('subsidio_consumo_acu', 12, 2)->default(0);
            $table->decimal('subsidio_cargo_fijo_acu', 12, 2)->default(0);
            $table->decimal('subsidio_vertimiento_alca', 12, 2)->default(0);
            $table->integer('frecuencia_aseo')->nullable();
            $table->integer('residuos_aforados_aseo')->nullable();
            $table->decimal('subsidio_aseo', 12, 2)->default(0);

            // Promedios
            $table->integer('prom_acu_1')->nullable();
            $table->integer('prom_acu_2')->nullable();
            $table->integer('prom_acu_3')->nullable();
            $table->integer('prom_acu_4')->nullable();
            $table->integer('prom_acu_5')->nullable();
            $table->integer('prom_acu_6')->nullable();

            // Estado y ubicación
            $table->string('estado')->default('Pendiente');
            $table->string('sector')->nullable();
            $table->string('municipio')->nullable();
            $table->string('departamento')->nullable();

            // DIAN
            $table->string('estado_dian')->nullable();
            $table->string('cufe')->nullable();
            $table->date('fecha_validacion_dian')->nullable();

            // Identificadores
            $table->string('codigo_suscriptor')->nullable();
            $table->string('codigo_ruta')->nullable();
            $table->string('ruTa_entrega')->nullable();
            $table->string('numero_predial')->nullable();
            $table->string('numero_medidor')->nullable();
            $table->string('marca_medidor')->nullable();
            $table->string('diametro_medidor')->nullable();

            // Tarifas Acueducto
            $table->decimal('tarifa_cargo_fijo_acu', 12, 2)->default(0);
            $table->decimal('tarifa_consumo_basico_acu', 12, 2)->default(0);
            $table->decimal('tarifa_consumo_complementario_acu', 12, 2)->default(0);
            $table->decimal('tarifa_consumo_suntuario_acu', 12, 2)->default(0);
            $table->decimal('cmt_acu', 12, 2)->default(0);

            // Tarifas Alcantarillado
            $table->decimal('tarifa_cargo_fijo_alca', 12, 2)->default(0);
            $table->decimal('tarifa_vertimiento_basico_alca', 12, 2)->default(0);
            $table->decimal('tarifa_vertimiento_complementario_alca', 12, 2)->default(0);
            $table->decimal('cmt_alca', 12, 2)->default(0);

            // Tarifas Aseo
            $table->decimal('tarifa_comercializacion_aseo', 12, 2)->default(0);
            $table->decimal('tarifa_limpieza_aseo', 12, 2)->default(0);
            $table->decimal('tarifa_barrido_aseo', 12, 2)->default(0);
            $table->decimal('tarifa_aprovechamiento_aseo', 12, 2)->default(0);
            $table->decimal('tarifa_recoleccion_transporte_aseo', 12, 2)->default(0);
            $table->decimal('tarifa_disposicion_final_aseo', 12, 2)->default(0);
            $table->decimal('tarifa_tratamiento_aseo', 12, 2)->default(0);

            // Otros
            $table->integer('facturas_vencidas')->default(0);
            $table->string('otros_conceptos')->nullable();
            $table->decimal('valor_otros_conceptos', 12, 2)->default(0);
            $table->decimal('saldo_a_favor', 12, 2)->default(0);

            // Consumos (campos duplicados que dejaste al final)
            $table->integer('consumo_basico_acu')->nullable();
            $table->integer('consumo_complementario_acu')->nullable();
            $table->string('acueducto')->nullable();
            $table->string('alcantarillado')->nullable();
            $table->string('aseo')->nullable();

            $table->timestamps();
        });

        // Ajustar el AUTO_INCREMENT inicial según motor
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE facturas_servicios_publicos AUTO_INCREMENT = 15056");
        } elseif ($driver === 'sqlite') {
            DB::statement("INSERT INTO sqlite_sequence (name, seq) VALUES ('facturas_servicios_publicos', 15056)");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas_servicios_publicos');
    }
};
