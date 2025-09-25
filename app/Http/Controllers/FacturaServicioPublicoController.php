<?php

namespace App\Http\Controllers;

use App\Models\FacturaServicioPublico;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use FPDF;


class FacturaServicioPublicoController extends Controller
{

    protected $fpdf;

    public function __construct()
    {
        $this->fpdf = new FPDF();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = FacturaServicioPublico::query();

            // Apply filters if provided
            if ($request->has('query')) {
                if ($request->filled('nit_cliente')) {
                    $query->where('nit_cliente', 'like', '%' . trim($request->input('nit_cliente')) . '%');
                }

                if ($request->filled('contrato')) {
                    $query->orWhere('contrato', 'like', '%' . trim($request->input('contrato')) . '%');
                }

                if ($request->filled('periodo')) {
                    $query->orWhere('periodo', 'like', '%' . trim($request->input('periodo')) . '%');
                }

                if ($request->filled('sector')) {
                    $query->orWhere('sector', 'like', '%' . trim($request->input('sector')) . '%');
                }

                if ($request->filled('suscriptor') && trim($request->input('suscriptor')) !== '') {
                    $query->where('suscriptor', 'like',  trim($request->input('suscriptor')) );
                }

            }

            // Paginate the results
            $all = $query->paginate(10);

            return response()->json($all);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch data', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*
        try {
            if ($request->has('id') && !empty($request->id)) {
                // Buscar el registro existente
                $all = FacturaServicioPublico::findOrFail($request->id);

                // Actualizar con los nuevos datos
                $all->update([
                    'codigo'       => $request->input('codigo'),
                    'nombre_corto' => $request->input('nombre_corto'),
                    'nombre'       => $request->input('nombre'),
                    'estado'       => $request->input('estado'),
                ]);
                $all = FacturaServicioPublico::paginate(10);
                return response()->json($all, 200);
            } else {
                // Si no hay id → crear nuevo
                $tipoDocumento = FacturaServicioPublico::create([
                    'codigo'       => $request->input('codigo'),
                    'nombre_corto' => $request->input('nombre_corto'),
                    'nombre'       => $request->input('nombre'),
                    'estado'       => $request->input('estado', 'ACTIVO'),
                ]);
                $tipoDocumentos = FacturaServicioPublico::paginate(10);

                return response()->json($tipoDocumentos, 201);
            }

        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Failed to save resource',
                'message' => $e->getMessage()
            ], 500);
        }
*/

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $all = FacturaServicioPublico::findOrFail($id);

            return response()->json($all);
        } catch (Exception $e) {
            return response()->json(['error' => 'Resource not found', 'message' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $all = FacturaServicioPublico::findOrFail($id);
            $all->delete();

            return response()->json(['message' => 'Registro eliminado con éxito']);
        } catch (Exception $e) {
            return response()->json(['error' => 'El registro está en uso o acción restringida', 'message' => $e->getMessage()], 500);
        }
    }

    public function importData(Request $request)
    {
        try {
           // Quitamos la primera fila (cabecera)
            $registros = array_slice($request->input('data'), 1);

            DB::beginTransaction();
            $totalInserted = 0;
            $total = count($registros);
            try {
                foreach ($registros as $row) {
                    // Validamos que existan los datos o las 4 columnas
                    // Rellenamos hasta 73 columnas (o el número que esperes máximo)
                    $row = array_pad($row, 73, null);


                    $res = DB::table('facturas_servicios_publicos')->insertOrIgnore([

                        'contrato'                          => $row[0],
                        'nit_cliente'                       => $row[1],
                        'suscriptor'                        => $row[2],
                        'direccion'                         => $row[3],
                        'estrato'                           => $row[4],
                        'uso'                               => $row[5],
                        'periodo'                           => $row[6],
                        'desde_hasta'                       => $row[7],
                        'dias_consumo'                      => $row[8],
                        'fecha_factura'                     => $row[9],
                        'fecha_limite_pago'                 => $row[10],
                        'total_acueducto'                   => $row[11],
                        'total_alcantarillado'              => $row[12],
                        'total_aseo'                        => $row[13],
                        'consumo_total_acueducto'           => $row[14],
                        'consumo_basico_acueducto'          => $row[15],
                        'consumo_complementario_acueducto'  => $row[16],
                        'consumo_suntuario_acueducto'       => $row[17],
                        'frecuencia_recoleccion'            => $row[18],
                        'total_factura'                     => $row[19],
                        'lectura_anterior'                  => $row[20],
                        'lectura_actual'                    => $row[21],
                        'subsidio_consumo_acu'              => $row[22],
                        'subsidio_cargo_fijo_acu'           => $row[23],
                        'subsidio_vertimiento_alca'         => $row[24],
                        'frecuencia_aseo'                   => $row[25],
                        'residuos_aforados_aseo'            => $row[26],
                        'subsidio_aseo'                     => $row[27],
                        'prom_acu_1'                        => $row[28],
                        'prom_acu_2'                        => $row[29],
                        'prom_acu_3'                        => $row[30],
                        'prom_acu_4'                        => $row[31],
                        'prom_acu_5'                        => $row[32],
                        'prom_acu_6'                        => $row[33],
                        'estado'                            => $row[34],
                        'sector'                            => $row[35],
                        'municipio'                         => $row[36],
                        'departamento'                      => $row[37],
                        'estado_dian'                       => $row[38],
                        'cufe'                              => $row[39],
                        'fecha_validacion_dian'             => $row[40],
                        'codigo_suscriptor'                 => $row[41],
                        'codigo_ruta'                       => $row[42],
                        'ruta_entrega'                      => $row[43],
                        'numero_predial'                    => $row[44],
                        'numero_medidor'                    => $row[45],
                        'marca_medidor'                     => $row[46],
                        'diametro_medidor'                  => $row[47],

                        // Tarifas Acueducto
                        'tarifa_cargo_fijo_acu'             => $row[48],
                        'tarifa_consumo_basico_acu'         => $row[49],
                        'tarifa_consumo_complementario_acu' => $row[50],
                        'tarifa_consumo_suntuario_acu'      => $row[51],
                        'cmt_acu'                           => $row[52],

                        // Tarifas Alcantarillado
                        'tarifa_cargo_fijo_alca'                   => $row[53],
                        'tarifa_vertimiento_basico_alca'           => $row[54],
                        'tarifa_vertimiento_complementario_alca'   => $row[55],
                        'cmt_alca'                                 => $row[56],

                        // Tarifas Aseo
                        'tarifa_comercializacion_aseo'        => $row[57],
                        'tarifa_limpieza_aseo'                => $row[58],
                        'tarifa_barrido_aseo'                 => $row[59],
                        'tarifa_aprovechamiento_aseo'         => $row[60],
                        'tarifa_recoleccion_transporte_aseo'  => $row[61],
                        'tarifa_disposicion_final_aseo'       => $row[62],
                        'tarifa_tratamiento_aseo'             => $row[63],

                        // Otros
                        'facturas_vencidas'       => $row[64],
                        'otros_conceptos'         => $row[65],
                        'valor_otros_conceptos'   => $row[66],
                        'saldo_a_favor'           => $row[67],

                        // Consumos adicionales
                        'consumo_basico_acu'              => $row[68],
                        'consumo_complementario_acu'      => $row[69],
                        'acueducto'      => $row[70],
                        'alcantarillado'      => $row[71],
                        'aseo'      => $row[72],

                        'created_at'              => now(),
                        'updated_at'              => now(),
                    ]);

                    if($res) $totalInserted++;

                }

                DB::commit();
                $all = FacturaServicioPublico::paginate(10);



                return response()->json(['message' => "Insertados: $totalInserted de $total", 'facturas' => $all ]);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Falló operación', 'message' => $e->getMessage()], 500);
        }
    }

    public function showInvoice(Request $request, $id)
    {
        try{

            $f = FacturaServicioPublico::findOrFail($id);


            $this->fpdf->AddPage('L', 'A4'); // Página vertical, tamaño A4
            // usa tu margen inferior real si lo sabes
            $this->fpdf->SetAutoPageBreak(false);
            $this->fpdf->SetAutoPageBreak(true, 10);
            $this->fpdf->SetFont('Arial', '', 10);

            // fondo
            $this->fpdf->Image(public_path('/assets/images/fondo_factura.jpg'), 0, 2, 290); // Cambia 'logo.png' por tu archivo

            // bloque 1

            // DATOS CLIENTE
            $this->fpdf->SetFont('Arial', '', 10);
            $this->fpdf->SetXY(33, 22.5);
            $this->fpdf->Cell(50, 10, $f->contrato, 0, 1, 'L');

            $this->fpdf->SetFont('Arial', 'B', 12);
            $this->fpdf->SetXY(53.5, 28.5);
            $this->fpdf->SetTextColor("255","0","0");
            $this->fpdf->Cell(50, 10, $f->id, 0, 1, 'L');

            $this->fpdf->SetTextColor("0","0","0");
            $this->fpdf->SetFont('Arial', '', 9);
            $this->fpdf->SetXY(85, 20.5);
            $this->fpdf->Cell(50, 10, Carbon::parse($f->fecha_limite_pago)->format('d/m/Y'), 0, 1, 'L');

            $this->fpdf->SetXY(25, 33);
            $this->fpdf->Cell(50, 10, $f->nit_cliente, 0, 1, 'L');


            $this->fpdf->SetXY(83, 33);
            $this->fpdf->Cell(50, 10, $f->codigo_suscriptor, 0, 1, 'L');

            $this->fpdf->SetFont('Arial', 'B', 9);
            $this->fpdf->SetXY(29.5, 37.5);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($f->suscriptor, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

            $this->fpdf->SetFont('Arial', '', 9);
            $this->fpdf->SetXY(29, 41);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($f->municipio." - ".$f->departamento, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

            $this->fpdf->SetXY(29, 44.3);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($f->direccion, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

            $this->fpdf->SetXY(25, 47.5);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($f->estrato, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

            $this->fpdf->SetXY(36, 47.5);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($f->uso, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

            $this->fpdf->SetXY(74.5, 47.8);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($f->codigo_ruta, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

            $this->fpdf->SetXY(46, 57.5);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($f->desde_hasta, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

            $this->fpdf->SetXY(41, 60.5);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($f->dias_consumo, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');


            //bloque 2
            $this->fpdf->SetXY(132, 15);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($f->numero_medidor, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

            $this->fpdf->SetXY(159, 15);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($f->periodo, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

            $this->fpdf->SetXY(237, 15.1);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($f->lectura_anterior." M3", 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

            $this->fpdf->SetXY(237, 18.1);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($f->lectura_actual." M3", 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

            $consumo_actual = $f->lectura_actual - $f->lectura_anterior;
            $this->fpdf->SetXY(237, 21.2);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($consumo_actual." M3", 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

            $this->fpdf->SetXY(203, 32);
            $this->fpdf->Cell(50, 10, mb_convert_encoding("PROM", 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

            $this->fpdf->SetXY(215, 32);
            $this->fpdf->Cell(50, 10, mb_convert_encoding($f->prom_acu_1, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

            $this->fpdf->SetXY(225, 32);
            $this->fpdf->Cell(50, 10, mb_convert_encoding($f->prom_acu_2, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

            $this->fpdf->SetXY(235, 32);
            $this->fpdf->Cell(50, 10, mb_convert_encoding($f->prom_acu_3, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

            $this->fpdf->SetXY(245, 32);
            $this->fpdf->Cell(50, 10, mb_convert_encoding($f->prom_acu_4, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

            $this->fpdf->SetXY(255, 32);
            $this->fpdf->Cell(50, 10, mb_convert_encoding($f->prom_acu_5, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

            $this->fpdf->SetFont('Arial', 'B', 12);
            $this->fpdf->SetXY(266, 31);
            $this->fpdf->Cell(50, 10, mb_convert_encoding(($f->prom_acu_6), 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

            $this->fpdf->SetFont('Arial', '', 8);

            //detalle tarifas acueducto
            $this->fpdf->SetXY(188.5, 55.5);
            $this->fpdf->Cell(50, 10, mb_convert_encoding("CARGO FIJO.  ".number_format($f->tarifa_cargo_fijo_acu,2,",","."), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

            $this->fpdf->SetXY(188.5, 59.5);
            $this->fpdf->Cell(50, 10, mb_convert_encoding("CONS.BÁSICO.  ".number_format($f->tarifa_consumo_basico_acu,2,",","."), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

            $this->fpdf->SetXY(188.5, 63.5);
            $this->fpdf->Cell(50, 10, mb_convert_encoding("C.COMPLEM.  ".number_format($f->tarifa_consumo_complementario_acu,2,",","."), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

            $this->fpdf->SetXY(188.5, 67.5);
            $this->fpdf->Cell(50, 10, mb_convert_encoding("C.M.T.     ".number_format($f->cmt_acu,2,",","."), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

            $aplicasubsidio=false;
            if($f->subsidio_cargo_fijo_acu<0){
                $aplicasubsidio=true;
            }
            $this->fpdf->SetXY(188.5, 71.5);
            if($aplicasubsidio==true){
                $this->fpdf->Cell(50, 10, mb_convert_encoding("SUBS.CF CS: $f->subsidio_cargo_fijo_acu% $f->subsidio_consumo_acu%", 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
            }else{
                $this->fpdf->Cell(50, 10, mb_convert_encoding("CONTR.CF CS: $f->subsidio_cargo_fijo_acu% $f->subsidio_consumo_acu%", 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
            }

             //detalle tarifas aseo
            $this->fpdf->SetXY(220.5, 55.5);
            $this->fpdf->Cell(50, 10, mb_convert_encoding("T.COMERCIA. ".number_format($f->tarifa_comercializacion_aseo,2,",","."), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

            $this->fpdf->SetXY(220.5, 59.5);
            $this->fpdf->Cell(50, 10, mb_convert_encoding("T.LIMPIEZA.  ".number_format($f->tarifa_limpieza_aseo,2,",","."), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

            $this->fpdf->SetXY(220.5, 63.5);
            $this->fpdf->Cell(50, 10, mb_convert_encoding("T.BARRIDO.     ".number_format($f->tarifa_barrido_aseo,2,",","."), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

            $this->fpdf->SetXY(220.5, 67.5);
            $this->fpdf->Cell(50, 10, mb_convert_encoding("T.APROVEC. ".number_format($f->tarifa_aprovechamiento_aseo,2,",","."), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

            $this->fpdf->SetXY(220.5, 71.5);
            $this->fpdf->Cell(50, 10, mb_convert_encoding("T.REC.TRAN. ".number_format($f->tarifa_recoleccion_transporte_aseo,2,",","."), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

            $this->fpdf->SetXY(220.5, 75.5);
            $this->fpdf->Cell(50, 10, mb_convert_encoding("T.DISP.FINAL. ".number_format($f->tarifa_disposicion_final_aseo,2,",","."), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

            $this->fpdf->SetXY(220.5, 79.5);
            $this->fpdf->Cell(50, 10, mb_convert_encoding("T.TRAT.LIXIV.    ".number_format($f->tarifa_tratamiento_aseo,2,",","."), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

            $aplicasubsidio=false;
            if($f->subsidio_cargo_fijo_acu<0){
                $aplicasubsidio=true;
            }
            $this->fpdf->SetXY(220.5, 83.5);
            if($aplicasubsidio==true){
                $this->fpdf->Cell(50, 10, mb_convert_encoding("SUBSIDIO:       $f->subsidio_aseo%", 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
            }else{
                $this->fpdf->Cell(50, 10, mb_convert_encoding("CONTRIBU:       $f->subsidio_aseo%", 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
            }

            //detalle tarifas alcantarillado
            $this->fpdf->SetXY(188.5, 79.5);
            $this->fpdf->Cell(50, 10, mb_convert_encoding("CARGO.FIJO.    ".number_format($f->tarifa_cargo_fijo_alca,2,",","."), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

            $this->fpdf->SetXY(188.5, 83.5);
            $this->fpdf->Cell(50, 10, mb_convert_encoding("VERTIMIENTO.    ".number_format($f->tarifa_vertimiento_basico_alca,2,",","."), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

            $this->fpdf->SetXY(188.5, 87.5);
            $this->fpdf->Cell(50, 10, mb_convert_encoding("C.M.T.    ".number_format($f->cmt_alca,2,",","."), 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');

            $aplicasubsidio=false;
            if($f->subsidio_cargo_fijo_acu<0){
                $aplicasubsidio=true;
            }
            $this->fpdf->SetXY(188.5, 91.5);
            if($aplicasubsidio==true){
                $this->fpdf->Cell(50, 10, mb_convert_encoding("SUBSIDIO:       $f->subsidio_vertimiento_alca%", 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
            }else{
                $this->fpdf->Cell(50, 10, mb_convert_encoding("CONTRIBU:       $f->subsidio_vertimiento_alca%", 'ISO-8859-1', 'UTF-8'), 0, 1, 'R');
            }

            //bloque central
            $this->fpdf->SetFont('Arial', '', 9);

            $this->fpdf->SetXY(159, 24);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($f->numero_predial??'N/D', 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

            $this->fpdf->SetXY(132, 19);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($f->marca_medidor, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

            $this->fpdf->SetXY(132, 24);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($f->diametro_medidor, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');


            $subsidios="ACU: CF.".($aplicasubsidio==true?$f->subsidio_cargo_fijo_acu:0)."%  CS.".($aplicasubsidio==true?$f->subsidio_consumo_acu:0)."% ALCA: ".($aplicasubsidio==true?$f->subsidio_vertimiento_alca:0)."% Ase: ".($aplicasubsidio==true?$f->subsidio_aseo:0)."%";
            $this->fpdf->SetXY(126, 29);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($subsidios, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

            $contibucion="ACU: CF.".($aplicasubsidio==false?$f->subsidio_cargo_fijo_acu:0)."%  CS.".($aplicasubsidio==false?$f->subsidio_consumo_acu:0)."% ALC: ".($aplicasubsidio==false?$f->subsidio_vertimiento_alca:0)."% Ase: ".($aplicasubsidio==false?$f->subsidio_aseo:0)."%";
            $this->fpdf->SetXY(132, 33.5);
            $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding($contibucion, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

            //Detalle acueducto
            $this->fpdf->SetFont('Arial', '', 8);
            $cant=1;
            $subsidio_cargofijo=0;
            $subsidio_consumo_basico=0;
            $subsidio_acu=0;
            $this->fpdf->SetXY(111, 52);
            if($f->acueducto==="SI"){
                $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("   1   CARGO FIJO ACUE                         UND      ".$cant."                ".$f->tarifa_cargo_fijo_acu*$cant, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

                $this->fpdf->SetXY(111, 55.5);
                $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("   2   CONSUMO BÁSICO                         UND      ".$f->consumo_basico_acueducto."              ".$f->tarifa_consumo_basico_acu*$f->consumo_basico_acueducto, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

                $this->fpdf->SetXY(111, 59);
                $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("   3   CONSUMO COMPLE ACUE            UND      ".$f->consumo_complementario_acueducto."                 ".$f->tarifa_consumo_complementario_acu*$f->consumo_complementario_acueducto, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

                $this->fpdf->SetXY(111, 62.5);
                $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("   4   CONSUMO SUNTUA ACUE             UND      ".$f->consumo_suntuario_acueducto."                ".$f->tarifa_consumo_suntuario_acu*$f->consumo_suntuario_acueducto, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

                $subsidio_cargofijo = ($f->tarifa_cargo_fijo_acu*($f->subsidio_cargo_fijo_acu/100));
                $subsidio_consumo_basico = (($f->consumo_basico_acueducto*$f->tarifa_consumo_basico_acu)*($f->subsidio_consumo_acu/100));

                $this->fpdf->SetXY(111, 66);
                if($aplicasubsidio==true){
                    $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("   5   SUBSIDIO ACUE                              UND      ".$cant."                ".$subsidio_acu=$subsidio_cargofijo+$subsidio_consumo_basico, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');
                }else{
                    $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("   5   CONTRIBUCIÓN ACUE                     UND      ".$cant."                ".$subsidio_acu=$subsidio_cargofijo+$subsidio_consumo_basico, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');
                }
            }else{
                $this->fpdf->SetTextColor("255","0","0");
                $this->fpdf->SetXY(138, 60);
                $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("SERVICIO INACTIVO", 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

                $this->fpdf->SetTextColor("0","0","0");
            }

            //Alcantarillado
            $cant=1;
            $this->fpdf->SetXY(111, 76);
            $subsidio_alca=0;
            if($f->alcantarillado==="SI"){
                $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("   6   CARGO FIJO ALCANTARILLADO    UND      ".$cant."                ".$f->tarifa_cargo_fijo_alca*$cant, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

                $this->fpdf->SetXY(111, 79.5);
                $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("   7   VERTIMIENTO BÁSICO                    UND      ".$f->consumo_basico_acueducto."              ".$f->tarifa_vertimiento_basico_alca*$f->consumo_basico_acueducto, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

                $subsidio_cargo_fijo_alca = ($f->tarifa_cargo_fijo_alca*($f->subsidio_vertimiento_alca/100));
                $subsidio_consumo_basico_alca = (($f->consumo_basico_acueducto*$f->tarifa_vertimiento_basico_alca)*($f->subsidio_vertimiento_alca/100));

                $this->fpdf->SetXY(111, 83);
                if($aplicasubsidio==true){
                    $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("   8   SUBSIDIO ALCA                               UND      ".$cant."                ".$subsidio_alca=$subsidio_cargo_fijo_alca+$subsidio_consumo_basico_alca, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');
                }else{
                    $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("   8   CONTRIBUCIÓN ALCA                     UND      ".$cant."                ".$subsidio_alca=$subsidio_cargo_fijo_alca+$subsidio_consumo_basico_alca, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');
                }
            }else{
                $this->fpdf->SetTextColor("255","0","0");
                $this->fpdf->SetXY(138, 79);
                $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("SERVICIO INACTIVO", 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

                $this->fpdf->SetTextColor("0","0","0");
            }

            //Aseo
            $cant=1;
            $this->fpdf->SetXY(111, 92);
            $subsidio_aseo=0;
            if($f->aseo==="SI"){
                $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("   9   BARRIDO Y LIMPIEZA                      UND      ".$cant."                ".$ase1=$f->tarifa_barrido_aseo*$cant, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

                $this->fpdf->SetXY(111, 95.5);
                $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("  10  RECOLEC. Y TRANSPORTE            UND      ".$cant."              ".$ase2=$f->tarifa_recoleccion_transporte_aseo*$cant, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');
                $this->fpdf->SetXY(111, 99);
                $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("  11  COMERCIALIZACION                       UND      ".$cant."               ".$ase3=$f->tarifa_comercializacion_aseo*$cant, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');
                $this->fpdf->SetXY(111, 103);
                $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("  12  TRATAMIENTO                                  UND      ".$cant."               ".$ase4=$f->tarifa_tratamiento_aseo*$cant, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');
                $this->fpdf->SetXY(111, 107);
                $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("  13  LIMPIEZA URBANA                           UND      ".$cant."               ".$ase5=$f->tarifa_limpieza_aseo*$cant, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');
                $this->fpdf->SetXY(111, 111);
                $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("  14  APROVECHAMIENTO                       UND      ".$cant."               ".$ase6=$f->tarifa_aprovechamiento_aseo*$cant, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');
                $this->fpdf->SetXY(111, 115);
                $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("  15  DISPOSICIÓN FINAL                         UND      ".$cant."               ".$ase7=$f->tarifa_disposicion_final_aseo*$cant, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

                $subsidio_aseo = ($ase1+$ase2+$ase3+$ase4+$ase5+$ase6+$ase7)*($f->subsidio_aseo/100);
                $this->fpdf->SetXY(111, 118.5);


                if($aplicasubsidio==true){
                    $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("  16  SUBSIDIO ASEO                                UND      ".$cant."               ".$subsidio_aseo, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');
                }else{
                    $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("  16  CONTRIBUCIÓN ACUE                     UND      ".$cant."                ".$subsidio_aseo, 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');
                }
            }else{
                $this->fpdf->SetTextColor("255","0","0");
                $this->fpdf->SetXY(138, 106);
                $this->fpdf->Cell(50, 10, strtoupper(mb_convert_encoding("SERVICIO INACTIVO", 'ISO-8859-1', 'UTF-8')), 0, 1, 'L');

                $this->fpdf->SetTextColor("0","0","0");
            }

            $this->fpdf->SetFont('Arial', 'B', 9);
            // TOTALES
            if($f->acueducto==="SI" && $f->alcantarillado==="SI" && $f->aseo==="SI"){
                $total = $f->total_acueducto+$f->total_alcantarillado+$f->total_aseo+$f->valor_otros_conceptos+$f->saldo_a_favor+$f->facturas_vencidas;
                $totalSubsidio = $subsidio_acu+$subsidio_alca+$subsidio_aseo;
            }else if($f->acueducto==="SI" && $f->alcantarillado==="SI" && $f->aseo!=="SI"){
                $total = $f->total_acueducto+$f->total_alcantarillado+$f->valor_otros_conceptos+$f->saldo_a_favor+$f->facturas_vencidas;
                $totalSubsidio = $subsidio_acu+$subsidio_alca;
            }else if($f->acueducto==="SI" && $f->alcantarillado!=="SI" && $f->aseo==="SI"){
                $total = $f->total_acueducto+$f->total_aseo+$f->valor_otros_conceptos+$f->saldo_a_favor+$f->facturas_vencidas;
                $totalSubsidio = $subsidio_acu+$subsidio_aseo;
            }else if($f->acueducto!=="SI" && $f->alcantarillado==="SI" && $f->aseo==="SI"){
                $total = $f->total_alcantarillado+$f->total_aseo+$f->valor_otros_conceptos+$f->saldo_a_favor+$f->facturas_vencidas;
                $totalSubsidio = $subsidio_alca+$subsidio_aseo;
            }else if($f->acueducto==="SI" && $f->alcantarillado!=="SI" && $f->aseo!=="SI"){
                $total = $f->total_acueducto+$f->valor_otros_conceptos+$f->saldo_a_favor+$f->facturas_vencidas;
                $totalSubsidio = $subsidio_acu;
            }else if($f->acueducto!=="SI" && $f->alcantarillado==="SI" && $f->aseo!=="SI"){
                $total = $f->total_alcantarillado+$f->valor_otros_conceptos+$f->saldo_a_favor+$f->facturas_vencidas;
                $totalSubsidio = $subsidio_alca;
            }else if($f->acueducto!=="SI" && $f->alcantarillado!=="SI" && $f->aseo==="SI"){
                $total = $f->total_aseo+$f->valor_otros_conceptos+$f->saldo_a_favor+$f->facturas_vencidas;
                $totalSubsidio = $subsidio_aseo;
            }else{
                $total = $f->valor_otros_conceptos+$f->saldo_a_favor+$f->facturas_vencidas;
                $totalSubsidio = 0;
            }
            $totalPagar = $total+$totalSubsidio;

            $this->fpdf->SetXY(111, 135.5);
            $this->fpdf->MultiCell(73, 10, $f->otros_conceptos??"No hay conceptos adicionales facturados", 0,  'L',false);
            $this->fpdf->SetXY(184, 135.5);
            $this->fpdf->Cell(80, 10, "$ ".$f->valor_otros_conceptos??0,0, 1,  'L');

            $this->fpdf->SetXY(111, 140.5);
            $this->fpdf->MultiCell(73, 10, "Facturas vencidas", 0,  'L',false);
            $this->fpdf->SetXY(184, 140.5);
            $this->fpdf->Cell(80, 10, "$ ".$f->facturas_vencidas,0, 1,  'L');


            $this->fpdf->SetXY(49, 78.3);
            if($f->acueducto==="SI"){

                $this->fpdf->Cell(80, 10, $f->consumo_total_acueducto." M3                   $".number_format($f->total_acueducto,0,",","."),0, 1,  'L');//bloque resumen
            }else{
                $this->fpdf->Cell(80, 10, "   0                         Inactivo",0, 1,  'L');//bloque resumen
            }
            $this->fpdf->SetXY(49, 84.3);
            if($f->alcantarillado==="SI"){
                $this->fpdf->Cell(80, 10, $f->consumo_total_acueducto." M3                   $".number_format($f->total_alcantarillado,0,",","."),0, 1,  'L');//bloque resumen
            }else{
                $this->fpdf->Cell(80, 10, "   0                         Inactivo",0, 1,  'L');//bloque resumen
            }
            $this->fpdf->SetXY(46, 90.3);
            if($f->aseo==="SI"){
                $this->fpdf->Cell(80, 10, $f->frecuencia_recoleccion." Recol.                   $".number_format($f->total_aseo,0,",","."),0, 1,  'L');//bloque resumen
            }else{
                $this->fpdf->Cell(80, 10, "   0                         Inactivo",0, 1,  'L');//bloque resumen
            }
            $this->fpdf->SetXY(75, 96.3);
            $this->fpdf->Cell(80, 10, str_replace("-","-$",number_format($totalSubsidio,0,",",".")),0, 1,  'L');//bloque resumen

            $this->fpdf->SetXY(75, 102.3);
            $ajuste=substr($total+$totalSubsidio,-2);
            if($ajuste>50 || $ajuste<50){
                $totalPagar-=$ajuste;
                $this->fpdf->Cell(80, 10, "-$".number_format($ajuste,0,",","."),0, 1,  'L');//bloque resumen
            }else{
                $ajuste=0;
                $this->fpdf->Cell(80, 10, "$".number_format($ajuste,0,",","."),0, 1,  'L');//bloque resumen
            }
            $totalPagar= "$ ".number_format($totalPagar,0,",",".");


            $this->fpdf->SetXY(134, 148);
            $this->fpdf->Cell(80, 10, $totalPagar,0, 1,  'L');//bloque detalle

            $this->fpdf->SetXY(65, 6);
            $this->fpdf->SetFont('Arial', 'B', 18);
            $this->fpdf->Cell(50, 10, $totalPagar, 0, 1, 'L');

            $this->fpdf->SetXY(65, 169);
            $this->fpdf->Cell(50, 10, $totalPagar, 0, 1, 'L');

            $this->fpdf->SetFont('Arial', '', 10);

            $this->fpdf->SetXY(45, 177.5);
            $this->fpdf->Cell(50, 10, Carbon::parse(mb_convert_encoding($f->fecha_factura, 'ISO-8859-1', 'UTF-8'))->format('d/m/Y'), 0, 1, 'L');

            $this->fpdf->SetXY(45, 181.7);
            $this->fpdf->Cell(50, 10, Carbon::parse(mb_convert_encoding($f->fecha_limite_pago, 'ISO-8859-1', 'UTF-8'))->format('d/m/Y'), 0, 0, 'L');


            // SALIDA DEL PDF
            //$this->fpdf->Output();

            return response($this->fpdf->Output('S'))
            ->header('Content-Type', 'application/pdf');

        }catch(Exception $e){
            return response()->json(['error' => 'Resource not found', 'message' => $e->getMessage()], 404);
        }

    }


}
