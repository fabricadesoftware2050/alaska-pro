<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaServicioPublico extends Model
{

    protected $table = 'facturas_servicios_publicos';

    protected $fillable = [
        'contrato','nit_cliente','suscriptor','direccion','estrato','uso',
        'periodo','desde_hasta','dias_consumo','fecha_factura','fecha_limite_pago',
        'total_acueducto','total_alcantarillado','total_aseo','consumo_total_acueducto',
        'consumo_basico_acueducto','consumo_complementario_acueducto','consumo_suntuario_acueducto',
        'frecuencia_recoleccion','total_factura','lectura_anterior','lectura_actual',
        'subsidio_consumo_acu','subsidio_cargo_fijo_acu','subsidio_vertimiento_alca',
        'frecuencia_aseo','residuos_aforados_aseo','subsidio_aseo',
        'prom_acu_1','prom_acu_2','prom_acu_3','prom_acu_4','prom_acu_5','prom_acu_6',
        'estado','sector','municipio','departamento',
        'estado_dian','cufe','fecha_validacion_dian',
        'codigo_suscriptor','codigo_ruta','ruta_entrega','numero_predial',
        'numero_medidor','marca_medidor','diametro_medidor',
        'tarifa_cargo_fijo_acu','tarifa_consumo_basico_acu','tarifa_consumo_complementario_acu',
        'tarifa_consumo_suntuario_acu','cmt_acu',
        'tarifa_cargo_fijo_alca','tarifa_vertimiento_basico_alca','tarifa_vertimiento_complementario_alca',
        'cmt_alca',
        'tarifa_comercializacion_aseo','tarifa_limpieza_aseo','tarifa_barrido_aseo',
        'tarifa_aprovechamiento_aseo','tarifa_recoleccion_transporte_aseo',
        'tarifa_disposicion_final_aseo','tarifa_tratamiento_aseo',
        'facturas_vencidas','otros_conceptos','valor_otros_conceptos','saldo_a_favor',
        'consumo_basico_acu','consumo_complementario_acu',
        'acueducto','alcantarillado','aseo'
    ];

    // Si quieres formatear automáticamente fechas
    protected $casts = [
        //'fecha_factura' => 'date',
        //'fecha_limite_pago' => 'date',
    ];
}
