<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Type\Integer;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

use function Termwind\parse;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'El campo correo es obligatorio.',
            'email.email' => 'El correo no tiene el formato correcto.',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

    
        $credentials = request(['email', 'password']);
    
        
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Datos de acceso incorrectos. Por favor, verifica tus credenciales.'], Response::HTTP_UNAUTHORIZED);
        }

        $user = auth()->user();

        return $this->respondWithToken($token);
    }

    public function loginPagina(Request $request){
        try {
            //code..
            $validator = Validator::make($request->all(),[
                "numeros_introducidos" => "required|string|max:3",
                "posicion_1" => "required|integer|min:0|max:15",
                "posicion_2" => "required|integer|min:0|max:15",
                "posicion_3" => "required|integer|min:0|max:15",
                "n_tarjeta" => "required|string|exists:users,n_tarjeta",
                "password" => "required|string"
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
            }
            $user = User::where('n_tarjeta', $request->n_tarjeta)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Datos de acceso incorrectos. Por favor, verifica tus credenciales.'], Response::HTTP_UNAUTHORIZED);
            }
    
            // Si es administrador, devuelve el token y la información del rol
            if ($user->rol === 'admin') {
                $token = auth()->login($user); // Genera el token para el usuario
                return response()->json([
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'rol' => 'administrador',
                ], Response::HTTP_OK);
            }
    
            // Validación del juego de barcos para usuarios no administradores
            if (!$this->validarNumeroTarjeta($request->posicion_1,$request->posicion_2,$request->posicion_3,$request->numeros_introducidos,$user->n_barcos)) {
                return response()->json(['error' => 'Las letras introducidas no coinciden con las solicitadas.'], Response::HTTP_UNAUTHORIZED);
            }
    
            // Si la validación del juego de barcos pasa, devuelve el token
            $token = auth()->login($user); // Genera el token para el usuario
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'rol' => $user->rol, // Puedes devolver el rol del usuario aquí
            ], Response::HTTP_OK);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo iniciar la sesión', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function unauthorized()
    {
        return redirect(route('login'));
    }

    
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100', 'min:2'],
            'password' => ['required', 'string', 'min:8'],
            'email' => ['required', 'email', 'unique:users,email'],// Agregamos la validación para id_campus
            'primer_apellido' => ['nullable', 'string'],
            'segundo_apellido' => ['nullable', 'string']
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'name.max' => 'El nombre no puede tener más de :max caracteres.',
            'email.required' => 'El campo correo es obligatorio.',
            'email.unique' => 'El correo ya está registrado.',
            'email.email' => 'El correo no tiene el formato correcto.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'El campo contraseña debe tener un minimo :min caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $exists = User::where('email', htmlspecialchars($request->input('email')))->first();
        if (!$exists) {

            $new = new User();
            $new->name = $request->get('name');
            $request->get('primer_apellido') ? $new->primer_apellido = $request->get('primer_apellido') : $new->primer_apellido = "" ;
            $request->get('segundo_apellido') ? $new->segundo_apellido = $request->get('segundo_apellido') : $new->segundo_apellido = "" ;
            $new->email = $request->get('email');
            $new->password = Hash::make($request->get('password'));
            $new->rol = 'usuario';
            $new->n_tarjeta = $this->crearNumTarjeta();
            $new->n_barcos = $this->crearNumTarjeta();
        
            $new->save();

            return response()->json($new, Response::HTTP_CREATED);
        }else{
            return response()->json(['error' => 'Ya existe un usuario con ese email'], Response::HTTP_BAD_REQUEST);
        }
    }


    public function registerAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100', 'min:2'],
            'password' => ['required', 'string', 'min:8'],
            'email' => ['required', 'email', 'unique:users,email'],  
            'primer_apellido' => ['nullable', 'string'],
            'segundo_apellido' => ['nullable', 'string'],
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos :min caracteres.',
            'name.max' => 'El nombre no puede tener más de :max caracteres.',
            'email.required' => 'El campo correo es obligatorio.',
            'email.unique' => 'El correo ya está registrado.',
            'email.email' => 'El correo no tiene el formato correcto.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'El campo contraseña debe tener un minimo :min caracteres.'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $exists = User::where('email', htmlspecialchars($request->input('email')))->first();
        if (!$exists) {

            $new = new User();
            $new->name = $request->get('name');
            $request->get('primer_apellido') ? $new->primer_apellido = $request->get('primer_apellido') : $new->primer_apellido = "" ;
            $request->get('segundo_apellido') ? $new->segundo_apellido = $request->get('segundo_apellido') : $new->segundo_apellido = "" ;
            $new->email = $request->get('email');
            $new->password = Hash::make($request->get('password'));
            $new->rol = 'administrador';
    
            if($request->file('image') != null){
                ImageController::cargarImagen($request,$new);
            }
            $new->save();

            return response()->json($new, Response::HTTP_CREATED);
        }else{
            return response()->json(['error' => 'Ya existe un usuario con ese email'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Valida si el token es válido
     */
    public function validateToken()
    {
        try {
            // Si el token es válido, auth()->user() no lanzará una excepción
            $user = auth()->user();
            return response()->json(['message' => 'Token válido','data' => auth()->user()], Response::HTTP_OK);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al validar el token'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function meData(){
        try {
            // Si el token es válido, auth()->user() no lanzará una excepción
            $user = auth()->user();
            return response()->json(['message' => 'Token válido','data' => auth()->user()->id], Response::HTTP_OK);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al validar el token'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        try {
            $token = JWTAuth::getToken(); // Obtiene el token actual
            if (!$token) {
                return response()->json(['error' => 'Token no encontrado'], Response::HTTP_BAD_REQUEST);
            }
            // Invalida el token actual
            JWTAuth::invalidate($token);
            return response()->json(['message' => 'Sesión cerrada correctamente'], Response::HTTP_OK);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo cerrar la sesión'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $token = JWTAuth::getToken(); // Obtiene el token actual
            if (!$token) {
                return response()->json(['error' => 'Token no encontrado'], Response::HTTP_BAD_REQUEST);
            }
            $nuevo_token = auth()->refresh();
            // Invalida el token actual
            JWTAuth::invalidate($token);
            return $this->respondWithToken($nuevo_token);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo cerrar la sesión'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,// El token en sí
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], Response::HTTP_OK);
    }

    protected function crearNumTarjeta() {
        $numTarjeta = '';
        for ($i = 0; $i < 16; $i++) {
            $numTarjeta .= rand(0, 9);
        }
        return $numTarjeta;
    }

    protected function validarNumeroTarjeta($posicion1, $posicion2, $posicion3, $numeroIntroducidos, $nBarcos) {
        $nBarcos = strval($nBarcos); 
        $longitudNBarcos = strlen($nBarcos);
    
        if ($posicion1 < 0 || $posicion1 >= $longitudNBarcos ||
            $posicion2 < 0 || $posicion2 >= $longitudNBarcos ||
            $posicion3 < 0 || $posicion3 >= $longitudNBarcos) {
            return false; // Posición fuera de rango
        }

        
        if ($nBarcos[$posicion1] == $numeroIntroducidos[0] &&
            $nBarcos[$posicion2] == $numeroIntroducidos[1] &&
            $nBarcos[$posicion3] == $numeroIntroducidos[2]) {
            return true;
        } else {
            return false;
        }
    }

}
