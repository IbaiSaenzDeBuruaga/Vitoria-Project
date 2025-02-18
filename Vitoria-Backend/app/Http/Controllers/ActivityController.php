<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\ActivityCentro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CentroCivico;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function all(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $activities = Activity::paginate($perPage);

            return response()->json($activities, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve activities', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function todos(){
        try {
            $activities = Activity::all();

            return response()->json($activities, Response::HTTP_OK);  
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
            return response()->json(['message' => 'Activity deleted successfully'], Response::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete activity', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addCentroCivicoToActivity(Request $request, Activity $activity, CentroCivico $centroCivico)
    {
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'fecha_fin' => 'required|date',
            'hora_fin' => 'required|date_format:H:i',
            'dias'=> 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {

            $activityCentro = new ActivityCentro();
            $activityCentro->activity_id = $activity->id;
            $activityCentro->centro_id = $centroCivico->id;
            $activityCentro->fecha_inicio = $request->fecha_inicio;
            $activityCentro->fecha_fin = $request->fecha_fin;
            $activityCentro->hora_inicio = $request->hora_inicio;
            $activityCentro->hora_fin = $request->hora_fin;
            $activityCentro->dias = $request->dias;
            $activityCentro->save();


            return response()->json(['message' => $activity->nombre.' añadida al '.$centroCivico->nombre.' con éxito'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to add centro civico to activity', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function removeCentroCivicoFromActivity(Activity $activity, CentroCivico $centroCivico)
    {
        try {
            $actividadCentro = ActivityCentro::where('activity_id',$activity->id)->where('centro_id',$centroCivico->id);
            $actividadCentro->delete();

            return response()->json(['message' => 'Centro civico removed from activity successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to remove centro civico from activity', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function allCentroCivicoActivity(){
        try{
            $activitiesCentros = ActivityCentro::with('activity')->get();
            return response()->json(['message' => 'Solicitud realizada con éxito','ActividadesCentro'=>$activitiesCentros], Response::HTTP_OK);

        }
        catch(Exception $e){
            return response()->json(['message' => 'Failed get activities from centrocivicos', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function countActivitiesPorCentro($centro_id)
    {
        try {
            $countActividadesPorCentro = ActivityCentro::where('centro_id', $centro_id)->count();
            return response()->json(['message' => 'Er diablo, MamaHuevo', 'totalActividades' => $countActividadesPorCentro], Response::HTTP_OK);

        } catch (Exception $e){
            return response()->json(['message' => 'Failed get activities from centrocivicos', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
