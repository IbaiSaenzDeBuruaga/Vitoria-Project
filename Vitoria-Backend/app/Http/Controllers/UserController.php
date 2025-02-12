<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'primer_apellido' => 'required|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'n_tarjeta' => 'required|integer',
            'n_barcos' => 'required|integer',
            'rol' => 'nullable|in:admin,usuario', // Optional, with allowed values
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            // Authorization check (e.g., can('create', User::class))
            $user = User::create([
                'name' => $request->name,
                'primer_apellido' => $request->primer_apellido,
                'segundo_apellido' => $request->segundo_apellido,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hash the password!
                'n_tarjeta' => $request->n_tarjeta,
                'n_barcos' => $request->n_barcos,
                'rol' => $request->rol ?? 'usuario', // Default to 'usuario' if not provided
            ]);

            return response()->json(['data' => $user, 'message' => 'User created successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create user', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
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
            'name' => 'sometimes|string|max:255',  // 'sometimes' means only validate if present
            'primer_apellido' => 'sometimes|string|max:255',
            'segundo_apellido' => 'nullable|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id, // Exclude current user from unique check
            'password' => 'sometimes|string|min:8',
            'n_tarjeta' => 'sometimes|integer',
            'n_barcos' => 'sometimes|integer',
            'rol' => 'nullable|in:admin,usuario',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            // Authorization check (e.g., can('update', $user))

            $user->fill($request->except('password')); // Fill all except password

            if ($request->filled('password')) {  // Only update password if provided
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return response()->json(['data' => $user, 'message' => 'User updated successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
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