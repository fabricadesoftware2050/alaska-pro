<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\FacturaServicioPublicoController;
use App\Http\Controllers\TipoDocumentoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
 Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class,'login']); // abierta
 });
    Route::group(['middleware' => 'auth:api'], function () {
        Route::group(['prefix' => 'auth'], function () {

            Route::get('logout', [AuthController::class,'logout']);
            Route::post('refresh', [AuthController::class,'refresh']);
            Route::post('me', [AuthController::class,'me']);
        });

        Route::apiResource('/facturas', FacturaController::class);
        Route::apiResource('/tiposDocumentos', TipoDocumentoController::class);
        Route::post('/import_tiposDocumentos', [TipoDocumentoController::class,'importData']);
        Route::get('/import_tiposDocumentos', [TipoDocumentoController::class,'index']);
        Route::apiResource('/facturasMasivasServicios', FacturaServicioPublicoController::class);
        Route::post('/import_facturasMasivasServicios', [FacturaServicioPublicoController::class,'importData']);
        Route::get('/import_facturasMasivasServicios', [FacturaServicioPublicoController::class,'index']);


         Route::get('/pdf_facturasMasivasServicios/{id}', [FacturaServicioPublicoController::class,'showInvoice']);


    });

});


