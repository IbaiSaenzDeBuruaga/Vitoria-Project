<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Authorization check
            $activities = Activity::all();
            return response()->json(['data' => $activities], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve activities', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'imagen' => 'nullable|string|max:255', // Adjust validation as needed
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            // Authorization check
            $activity = Activity::create($request->all());
            return response()->json(['data' => $activity, 'message' => 'Activity created successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create activity', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        try {
            // Authorization check
            return response()->json(['data' => $activity], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve activity', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|string|max:255',
            'imagen' => 'nullable|string|max:255', // Adjust validation as needed
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            // Authorization check
            $activity->update($request->all());
            return response()->json(['data' => $activity, 'message' => 'Activity updated successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update activity', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        try {
            // Authorization check
            $activity->delete();
            return response()->json(['message' => 'Activity deleted successfully'], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete activity', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addCentroCivicoToActivity(Request $request, Activity $activity, CentroCivico $centroCivico)
{
    $validator = Validator::make($request->all(), [
        'fecha' => 'required|date',
        'horario_inicio' => 'required|date_format:H:i',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    try {
        $activity->centroCivicos()->attach($centroCivico, [
            'fecha' => $request->fecha,
            'horario_inicio' => $request->horario_inicio,
        ]);

        return response()->json(['message' => 'Centro civico added to activity successfully'], Response::HTTP_OK);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to add centro civico to activity', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

public function removeCentroCivicoFromActivity(Activity $activity, CentroCivico $centroCivico)
{
     try {
        $activity->centroCivicos()->detach($centroCivico);

        return response()->json(['message' => 'Centro civico removed from activity successfully'], Response::HTTP_OK);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to remove centro civico from activity', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
}