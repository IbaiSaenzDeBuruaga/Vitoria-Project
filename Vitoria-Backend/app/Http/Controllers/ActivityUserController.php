<?php

namespace App\Http\Controllers;

use App\Models\ActivityUser;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Escaper;

use const Dom\VALIDATION_ERR;

class ActivityUserController extends Controller
{
    public function all(){
        $activitiesUser = ActivityUser::with('user', 'activity')->get();
        return response()->json(['message' => 'Diselo luyan', 'data' => $activitiesUser], Response::HTTP_OK);
    }

    public function addActivityUser(Request $request){
        $validator = Validator::make($request->all(), [
            'activity_id' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $activityuser = new ActivityUser();
            $activityuser->user_id = auth()->user()->id;
            $activityuser->activity_id = $request->input('activity_id');
            $activityuser->save();

            return response()->json(['message' => 'Usuario asignado a la actividad', 'data' => $activityuser], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to add centro civico to activity', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function tusActividades(){   
        try{
            $user = auth()->user();        
            $activitiesUser = ActivityUser::where('user_id', $user->id)
            ->with('activity.activity') 
            ->get();
            if($activitiesUser){
                return response()->json(['message' => 'Actividades del usuario', 'data' => $activitiesUser], Response::HTTP_OK);
            }
        }
        catch(Exception $e){
            return response()->json(['message' => 'Ha sucedido un error al entregarte tus actividades', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteActivityUser(Request $request)
    {
        try{
            $activityUser = ActivityUser::find($request->input('activity_id'));
            if($activityUser && $activityUser->user_id == Auth()->id()){
                $activityUser->delete();
                return response()->json(['message' => 'Usuario borrado'], Response::HTTP_OK);
            }
            return response()->json(['message' => 'El usuario no es propietario de este recurso'], Response::HTTP_UNAUTHORIZED);
        }
        catch(Exception $e){
            return response()->json(['message' => 'No se ha podido borrar el usuario de la actividad', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
