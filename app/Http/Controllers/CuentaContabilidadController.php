<?php

namespace App\Http\Controllers;

use App\Models\CuentaContabilidad;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CuentaContabilidadController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = CuentaContabilidad::query();

            // Apply filters if provided
            if ($request->has('query')) {
                if ($request->filled('nombre')) {
                    $query->where('nombre', 'like', '%' . trim($request->input('nombre')) . '%');
                }


                if ($request->filled('nombre')) {
                    $query->orWhere('codigo', 'like', '%' . trim($request->input('nombre')) . '%');
                }


            }

            // Paginate the results
            $tipoDocumentos = $query->paginate(10);

            return response()->json($tipoDocumentos);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch data', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if ($request->has('id') && !empty($request->id)) {
                // Buscar el registro existente
                $modelo = CuentaContabilidad::findOrFail($request->id);

                // Actualizar con los nuevos datos
                $modelo->update([
                    'codigo'       => $request->input('codigo'),
                    'nombre' => $request->input('nombre'),
                    'tipo'       => $request->input('tipo'),
                    'estado'       => $request->input('estado'),
                    'naturaleza'       => $request->input('naturaleza'),
                    'nivel'       => $request->input('nivel'),
                    'padre_id'       => $request->input('padre_id'),
                    'activa'       => $request->input('activa'),
                    'descripcion'       => $request->input('descripcion')
                ]);
                $all = CuentaContabilidad::paginate(10);
                return response()->json($all, 200);
            } else {
                // Si no hay id → crear nuevo
                $modelo = CuentaContabilidad::create([
                    'codigo'       => $request->input('codigo'),
                    'nombre' => $request->input('nombre'),
                    'tipo'       => $request->input('tipo'),
                    'estado'       => $request->input('estado'),
                    'naturaleza'       => $request->input('naturaleza'),
                    'nivel'       => $request->input('nivel'),
                    'padre_id'       => $request->input('padre_id'),
                    'activa'       => $request->input('activa'),
                    'descripcion'       => $request->input('descripcion')
                ]);
                $all = CuentaContabilidad::paginate(10);

                return response()->json($all, 201);
            }

        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Failed to save resource',
                'message' => $e->getMessage()
            ], 500);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $modelo = CuentaContabilidad::findOrFail($id);

            return response()->json($modelo);
        } catch (\Exception $e) {
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
            $modelo = CuentaContabilidad::findOrFail($id);
            $modelo->delete();

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
                    if (count($row) < 1) {
                        continue;
                    }

                        $res=DB::table('cuentas_contables')->insert([
                            'codigo'       => $row[0],
                            'nombre' => $row[1],
                            'tipo'       => $row[2],
                            'naturaleza'       => $row[3],
                            'nivel'       => $row[4],
                            'padre_id'       => $row[5]||null,
                            'activa'       => $row[6]==1 ||$row[6]!="",
                            'descripcion'       => $row[7],
                            'created_at'   => now(),
                            'updated_at'   => now(),
                        ]);
                        if($res) $totalInserted++;

                }

                DB::commit();
                $all = CuentaContabilidad::paginate(10);



                return response()->json(['message' => "Insertados: $totalInserted de $total", 'data' => $all,'registros'=>$registros ]);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Falló operación', 'message' => $e->getMessage()], 500);
        }
    }
}
