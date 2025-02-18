<?php

namespace App\Http\Controllers;

use App\Models\ActivityUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class ActivityUserController extends Controller
{
    public function all()
    {
        $activitiesUser = ActivityUser::with('user', 'activity')->get();
        return response()->json(['message' => 'Diselo luyan', 'data' => $activitiesUser], Response::HTTP_OK);
    }

    public function addActivityUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $userId = auth()->user()->id;
            $activityId = $request->input('activity_id');

            // Verificar si ya existe una inscripción.  ¡Esta es la clave!
            $existingRegistration = ActivityUser::where('user_id', $userId)
                                                ->where('activity_id', $activityId)
                                                ->first();

            if ($existingRegistration) {
                return response()->json(['message' => 'El usuario ya está inscrito a esta actividad.'], Response::HTTP_CONFLICT); // 409 Conflict
            }


            $activityuser = new ActivityUser();
            $activityuser->user_id = $userId;
            $activityuser->activity_id = $activityId;
            $activityuser->save();

            return response()->json(['message' => 'Usuario asignado a la actividad', 'data' => $activityuser], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to add centro civico to activity', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function tusActividades()
    {
        try {
            $user = auth()->user();
            $activitiesUser = ActivityUser::where('user_id', $user->id)
                ->with('activityCentro.activity')
                ->get();
            if ($activitiesUser) {
                return response()->json(['message' => 'Actividades del usuario', 'data' => $activitiesUser], Response::HTTP_OK);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Ha sucedido un error al entregarte tus actividades', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteActivityUser(Request $request)
    {
        try {
            $activityId = $request->activity_id;
            $userId = auth()->user()->id;

            // Find the ActivityUser record using the composite key (user_id and activity_id)
            $activityUser = ActivityUser::where('user_id', $userId)
                ->where('activity_id', $activityId)
                ->first();

            if (!$activityUser) {
                return response()->json(['message' => 'ActivityUser record not found or not owned by user'], 404);
            }

            // Delete the record
            $activityUser->delete();

            return response()->json(['message' => 'ActivityUser deleted successfully'], 200);
        } catch (Exception $e) {
            \Log::error('Error deleting ActivityUser: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to delete ActivityUser', 'error' => $e->getMessage()], 500);
        }
    }

    public function isRegistered(Request $request, $activityId)  // <--- Nuevo método
    {
        try {
            $userId = auth()->user()->id; // Obtener el ID del usuario autenticado

            // Buscar la inscripción en la tabla pivote (ActivityUser)
            $isRegistered = ActivityUser::where('user_id', $userId)
                                        ->where('activity_id', $activityId)
                                        ->exists(); // Usar exists() para obtener un booleano

            return response()->json(['is_registered' => $isRegistered], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json(['message' => 'Error al verificar la inscripción', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}