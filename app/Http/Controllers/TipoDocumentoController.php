<?php

namespace App\Http\Controllers;

use App\Models\TiposDocumento;
use Illuminate\Http\Request;
use App\Models\TipoDocumento;
use Exception;
use Illuminate\Support\Facades\DB;

class TipoDocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = TiposDocumento::query();

            // Apply filters if provided
            if ($request->has('query')) {
                if ($request->filled('nombre')) {
                    $query->where('nombre', 'like', '%' . trim($request->input('nombre')) . '%');
                }

                if ($request->filled('nombre')) {
                    $query->orWhere('nombre_corto', 'like', '%' . trim($request->input('nombre')) . '%');
                }

                if ($request->filled('nombre')) {
                    $query->orWhere('codigo', 'like', '%' . trim($request->input('nombre')) . '%');
                }

                if ($request->filled('estado') && trim($request->input('estado')) !== '') {
                    $query->where('estado', 'like',  trim($request->input('estado')) );
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
                $tipoDocumento = TiposDocumento::findOrFail($request->id);

                // Actualizar con los nuevos datos
                $tipoDocumento->update([
                    'codigo'       => $request->input('codigo'),
                    'nombre_corto' => $request->input('nombre_corto'),
                    'nombre'       => $request->input('nombre'),
                    'estado'       => $request->input('estado'),
                ]);
                $tipoDocumentos = TiposDocumento::paginate(10);
                return response()->json($tipoDocumentos, 200);
            } else {
                // Si no hay id → crear nuevo
                $tipoDocumento = TiposDocumento::create([
                    'codigo'       => $request->input('codigo'),
                    'nombre_corto' => $request->input('nombre_corto'),
                    'nombre'       => $request->input('nombre'),
                    'estado'       => $request->input('estado', 'ACTIVO'),
                ]);
                $tipoDocumentos = TiposDocumento::paginate(10);

                return response()->json($tipoDocumentos, 201);
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
            $tipoDocumento = TiposDocumento::findOrFail($id);

            return response()->json($tipoDocumento);
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
            $tipoDocumento = TiposDocumento::findOrFail($id);
            $tipoDocumento->delete();

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
                    if (count($row) < 4) {
                        continue;
                    }

                        $res=DB::table('tipos_documentos')->insertOrIgnore([
                            'codigo'       => $row[0],
                            'nombre_corto' => $row[1],
                            'nombre'       => $row[2],
                            'estado'       => $row[3],
                            'created_at'   => now(),
                            'updated_at'   => now(),
                        ]);
                        if($res) $totalInserted++;

                }

                DB::commit();
                $tipoDocumentos = TiposDocumento::paginate(10);



                return response()->json(['message' => "Insertados: $totalInserted de $total", 'tipos' => $tipoDocumentos ]);
            } catch (Exception $e) {
                DB::rollBack();
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Falló operación', 'message' => $e->getMessage()], 500);
        }
    }
}
