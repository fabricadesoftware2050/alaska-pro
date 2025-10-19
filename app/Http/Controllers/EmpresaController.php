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
        //
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
