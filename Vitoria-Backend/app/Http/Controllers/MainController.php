<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Campus;
use App\Models\Seccion;
use App\Models\Maquina;
use App\Models\Incidencia;
use App\Models\MantenimientoPreventivo;
use App\Models\TipoIncidencia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Exception;

class MainController extends Controller
{
    public function cargaInicial()
    {
        try {
 
            

            return response()->json(['message' => 'Carga inicial completada con éxito!'], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error al realizar la carga inicial.',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}