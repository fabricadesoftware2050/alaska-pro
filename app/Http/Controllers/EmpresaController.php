<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if ($request->has('id') && !empty($request->id)) {
                // Buscar el registro existente
                $modelo = Empresa::findOrFail($request->id);

                // Actualizar con los nuevos datos
                $modelo->update([
                    'nit'       => $request->input('nit'),
                    'razon_social' => $request->input('razon_social'),
                    'siglas'       => $request->input('siglas'),
                    'nombre_representante_legal'       => $request->input('nombre_representante_legal'),
                    'nombre_contador'       => $request->input('nombre_contador'),
                    'matricula_contador'       => $request->input('matricula_contador'),
                    'nombre_revisor_fiscal'       => $request->input('nombre_revisor_fiscal'),
                    'matricula_revisor_fiscal'       => $request->input('matricula_revisor_fiscal'),
                    'url_logo'       => $request->input('url_logo'),
                    'estado'       => $request->input('estado'),
                ]);
                return response()->json($modelo, 200);
            } else {
                // Si no hay id → crear nuevo
                $modelo = Empresa::create([
                   'nit'       => $request->input('nit'),
                    'razon_social' => $request->input('razon_social'),
                    'siglas'       => $request->input('siglas'),
                    'nombre_representante_legal'       => $request->input('nombre_representante_legal'),
                    'nombre_contador'       => $request->input('nombre_contador'),
                    'matricula_contador'       => $request->input('matricula_contador'),
                    'nombre_revisor_fiscal'       => $request->input('nombre_revisor_fiscal'),
                    'matricula_revisor_fiscal'       => $request->input('matricula_revisor_fiscal'),
                    'url_logo'       => $request->input('url_logo'),
                    'estado'       => $request->input('estado','ACTIVO'),
                ]);


                return response()->json($modelo, 201);
            }

        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Failed to save resource',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        try {
            $entidad = Empresa::find($id);

            if (!$entidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'entidad not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $entidad
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving entidad',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $entidad = Empresa::find($id);

            if (!$entidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'entidad not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'nit' => 'sometimes|required|string|max:255',
                'razon_social' => 'sometimes|required|string|max:255',
                // Add other validation rules as needed
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $entidad->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $entidad
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating factura',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
