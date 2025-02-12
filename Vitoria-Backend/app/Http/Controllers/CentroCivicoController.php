<?php

namespace App\Http\Controllers;

use App\Models\CentroCivico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CentroCivicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Authorization check
            $centroCivicos = CentroCivico::all();
            return response()->json(['data' => $centroCivicos], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve centros civicos', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            // Authorization check
            $centroCivico = CentroCivico::create($request->all());
            return response()->json(['data' => $centroCivico, 'message' => 'Centro civico created successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create centro civico', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CentroCivico $centroCivico)
    {
        try {
            // Authorization check
            return response()->json(['data' => $centroCivico], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve centro civico', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CentroCivico $centroCivico)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|string|max:255',
            'direccion' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            // Authorization check
            $centroCivico->update($request->all());
            return response()->json(['data' => $centroCivico, 'message' => 'Centro civico updated successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update centro civico', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CentroCivico $centroCivico)
    {
        try {
            // Authorization check
            $centroCivico->delete();
            return response()->json(['message' => 'Centro civico deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete centro civico', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}