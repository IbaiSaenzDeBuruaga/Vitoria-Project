<?php

namespace App\Http\Controllers;

use App\Models\ActivityUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
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
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $activityuser = new ActivityUser();
            $activityuser->user_id = $request->input('user_id');
            $activityuser->activity_id = $request->input('activity_id');
            $activityuser->save();

            return response()->json(['message' => 'BRRRRRR, Real hasta la muerte', 'data' => $activityuser], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to add centro civico to activity', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
