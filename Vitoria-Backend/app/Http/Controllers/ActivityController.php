<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function all()
    {
        $activity = Activity::paginate(6);
        return response()->json(["message" => "Solicitud de actividades realizada con éxito",
        "data" => $activity],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        //
        try {
            $validator = Validator::make($request->all(),[
                'nombre' => ['required','max:255','min:2'],
                'image' =>  'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            ],
            [
                'nombre.required' => 'El nombre es obligatorio',
                'nombre.min' => 'El nombre debe tener al menos :min caracteres',
                'nombre.max' => 'El  nombre tiene un máximo de :max caracteres'
            ]);

            if($validator->fails()){
                return response()->json(['error' => $validator->messages()],Response::HTTP_BAD_REQUEST);
            }

            $actividad = new Activity();
            $actividad->nombre = $request->nombre;

            if($request->file('image') != null){
                ImageController::cargarImagenActividad($request,$actividad);
            }
            $actividad->save();

            return response()->json(["message"=>"Actividad creada con éxito",
            "data" => $actividad],Response::HTTP_CREATED);

        } 
        catch (Exception $e) {
            return response()->json(["error" => $e->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function show(Activity $activity = null)
    {
        //
        try{
        
            if($activity){
                return response()->json(['message'=>'Actividad solicitada con éxito',
                "data" => $activity],Response::HTTP_ACCEPTED);
            }
            else{
                return response()->json(['error' => 'No se ha encontrado actividad con ese id'],Response::HTTP_NOT_FOUND);
            }


        }
        catch(Exception $e){
            return response()->json(["error"=>$e->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);   
        }
    }



    public function update(Request $request, Activity $activity)
    {
        try{

            $validator = Validator::make($request->all(),[
              'nombre' => ['required','max:255','min:2'],
              'image' =>  'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
             [
                'nombre.required' => 'El nombre es obligatorio',
                'nombre.min' => 'El nombre debe tener al menos :min caracteres',
                'nombre.max' => 'El  nombre tiene un máximo de :max caracteres'
            ]);


            if($validator->fails()){
                return response()->json(['error' => $validator->messages()],Response::HTTP_BAD_REQUEST);
            }

            $actividad = $activity;
            
                $actividad->nombre = $request->nombre;
               
                if($request->file('image') != null){
                    ImageController::cargarImagenActividad($request,$actividad);
                }
                $actividad->save();
    

            return response()->json(["message"=>"Actividad actualizada con éxito",
            "data"=>$actividad],Response::HTTP_OK);

        }
        catch(Exception $e){
            return response()->json(["error" => $e->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);
        
        }
    }

    public function destroy(Activity $activity)
    {
        //
        try{
            if($activity){
                $activity->delete();
                return response()->json(['message'=>'Actividad eliminada con éxito'],Response::HTTP_OK);
            }
            else{
                return response()->json(['error' => 'No se ha encontrado actividad con ese id'],Response::HTTP_NOT_FOUND);
            }
        }
        catch(Exception $e){
            return response()->json(["error"=>$e->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);   
        }

    }
}
