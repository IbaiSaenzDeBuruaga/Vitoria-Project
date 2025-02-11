<?php

namespace App\Http\Controllers;

use App\Models\CentroCivico;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;  // Importa JsonResponse

class CentroCivicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $centrosCivicos = CentroCivico::all();
        return response()->json($centrosCivicos);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
        ]);

        $centroCivico = new CentroCivico();
        $centroCivico->nombre = $request->input('nombre');
        $centroCivico->direccion = $request->input('direccion');
        $centroCivico->save();

        return response()->json($centroCivico, 201); // 201 Created
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CentroCivico  $centroCivico
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(CentroCivico $centroCivico): JsonResponse
    {
        return response()->json($centroCivico);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CentroCivico  $centroCivico
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, CentroCivico $centroCivico): JsonResponse
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
        ]);

        $centroCivico->nombre = $request->input('nombre');
        $centroCivico->direccion = $request->input('direccion');
        $centroCivico->save();

        return response()->json($centroCivico);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CentroCivico  $centroCivico
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(CentroCivico $centroCivico): JsonResponse
    {
        $centroCivico->delete();

        return response()->json(null, 204); // 204 No Content
    }
}