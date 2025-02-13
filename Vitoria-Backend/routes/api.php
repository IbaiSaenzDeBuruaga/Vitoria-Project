<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CentroCivicoController;



Route::prefix('main')->group(function () {
    Route::get('carga-inicial', [MainController::class, 'cargaInicial']);
});

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::middleware('auth:api')->group(function () {
        //Route::post('register', [AuthController::class, 'register'])->middleware('admin');
        Route::post('register_tecnico', [AuthController::class, 'registerTecnico'])->middleware('admin');
        Route::get('me', [AuthController::class, 'me']);
        Route::get('me_data', [AuthController::class, 'meData']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('validate-token', [AuthController::class, 'validateToken']);
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    });
});

Route::prefix('image')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::post('upload', [ImageController::class, 'upload']);
    });
});

Route::prefix('activity')->group(function () {
    Route::get('/all',[ActivityController::class, 'allCentroCivicoActivity']);
    Route::get('{activity}',[ActivityController::class, 'show']);
    Route::middleware('auth:api')->group(function () {
        Route::post('/',[ActivityController::class,'store'])->middleware('admin');
        Route::put('{activity}/update' , [ActivityController::class,'update'])->middleware('admin');
        Route::delete('{activity}/delete',[ActivityController::class,'destroy'])->middleware('admin');
        Route::put('{activity}/centro/{centroCivico}',[ActivityController::class,'addCentroCivicoToActivity'])->middleware('admin');
    });
});

Route::prefix('activityUser')->group(function () {
    Route::get('/all', [ActivityUserController::class, 'all']);
    Route::post('/add', [ActivityUserController::class, 'addActivityUser']);
});



Route::middleware('auth:api')->group(function () {
    Route::resource('users', UserController::class);
});


Route::prefix('usuario')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::get('all',[UserController::class, 'all']);
        Route::get('{usuario}',[UserController::class, 'show'])->middleware('admin');
        Route::put('{usuario}/update',[UserController::class, 'update'])->middleware('admin');
        Route::delete('{usuario}/delete',[UserController::class,'destroy'])->middleware('admin');
        Route::put('reset_password',[UserController::class,'resetPassword']);
    });
});

Route::prefix('centros-civicos')->group(function () {
    Route::get('/all', [CentroCivicoController::class, 'index']);
    Route::get('{centroCivico}', [CentroCivicoController::class, 'show']);
    Route::middleware('auth:api')->group(function () {
        Route::post('/', [CentroCivicoController::class, 'store'])->middleware('admin'); //Solo admins pueden crear
        Route::put('{centroCivico}/update', [CentroCivicoController::class, 'update'])->middleware('admin'); //Solo admins pueden actualizar
        Route::delete('{centroCivico}', [CentroCivicoController::class, 'destroy'])->middleware('admin'); //Solo admins pueden eliminar
    });
});



//Route::get('/', [AuthController::class, 'unauthorized'])->name('login');
