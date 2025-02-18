<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Authorization check (e.g., can('viewAny', User::class))
            $users = User::all();
            return response()->json(['data' => $users], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve users', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(User $user)
    {
        try {
            // Authorization check (e.g., can('view', $user))
            return response()->json(['data' => $user], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve user', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'primer_apellido' => 'sometimes|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'email' => 'sometimes|string|email|max:255',
            'n_tarjeta' => 'sometimes',  
            'n_barcos' => 'sometimes',   
            'rol' => 'nullable|in:usuario,admin',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {


            if($user){


                DB::beginTransaction(); // Inicia la transacción

                // Usa $request->only() para mayor seguridad (Mass Assignment Protection)
                $userData = $request->only([
                    'name',
                    'primer_apellido',
                    'segundo_apellido',
                    'email',
                    'n_tarjeta',
                    'n_barcos',
                    'rol',
                ]);

                return response()->json('Mi gente el usuario es '.$userData[0]);


                $user->update($userData); // Actualiza el usuario con los datos permitidos


                DB::commit(); // Confirma la transacción

                return response()->json(['data' => $user, 'message' => 'User updated successfully'], Response::HTTP_OK);
            }
            else{
                return response()->json('No se encontró el usuario', Response::HTTP_NOT_FOUND);
            }
            

        } catch (\Exception $e) {
            DB::rollBack(); // Revierte la transacción en caso de error
            return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Authorization check (e.g., can('delete', $user))
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], Response::HTTP_NO_CONTENT); // 204 No Content
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete user', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}