<?php

use App\Http\Controllers\ActivityController;
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
    Route::post('activity',[ActivityController::class,'store']);
    Route::get('{activity}',[ActivityController::class, 'show']);
    Route::put('{activity}/update' , [ActivityController::class,'update']);
    Route::delete('{activity}/destroy',[ActivityController::class,'destroy']);
});




Route::middleware('auth:api')->group(function () {
    Route::resource('users', UserController::class);
});


Route::prefix('usuario')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::get('all',[UserController::class, 'all']);
        Route::get('show/{usuario}',[UserController::class, 'show'])->middleware('admin');
        Route::put('update/{usuario}',[UserController::class, 'update'])->middleware('admin');
        Route::put('enable/{usuario}',[UserController::class, 'enable'])->middleware('admin');
        Route::put('disable/{usuario}',[UserController::class, 'disable'])->middleware('admin');
        Route::delete('delete/{usuario}',[UserController::class,'destroy'])->middleware('admin');
        Route::put('reset_password',[UserController::class,'resetPassword']);

    

    });

    Route::prefix('centros-civicos')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::get('/', [CentroCivicoController::class, 'index']);
            Route::post('/', [CentroCivicoController::class, 'store'])->middleware('admin'); //Solo admins pueden crear
            Route::get('/{centroCivico}', [CentroCivicoController::class, 'show']);
            Route::put('/{centroCivico}', [CentroCivicoController::class, 'update'])->middleware('admin'); //Solo admins pueden actualizar
            Route::delete('/{centroCivico}', [CentroCivicoController::class, 'destroy'])->middleware('admin'); //Solo admins pueden eliminar
        });
    });
});


//Route::get('/', [AuthController::class, 'unauthorized'])->name('login');