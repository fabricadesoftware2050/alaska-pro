<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\FacturaServicioPublicoController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\TipoDocumentoController;
use App\Models\CuentaContabilidad;
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
        Route::apiResource('/empresas', EmpresaController::class);
        Route::apiResource('/cuentasContables', CuentaContabilidad::class);
        Route::post('/import_cuentasContables', [CuentaContabilidad::class,'importData']);
        Route::get('/import_cuentasContables', [CuentaContabilidad::class,'index']);
        Route::apiResource('/tiposDocumentos', TipoDocumentoController::class);
        Route::post('/import_tiposDocumentos', [TipoDocumentoController::class,'importData']);
        Route::get('/import_tiposDocumentos', [TipoDocumentoController::class,'index']);
        Route::apiResource('/facturasMasivasServicios', FacturaServicioPublicoController::class);
        Route::post('/import_facturasMasivasServicios', [FacturaServicioPublicoController::class,'importData']);
        Route::get('/import_facturasMasivasServicios', [FacturaServicioPublicoController::class,'index']);


        Route::get('/pdf_facturasMasivasServicios/{id}', [FacturaServicioPublicoController::class,'showInvoice']);

        Route::post('/upload', [FileController::class, 'subir']);
        Route::delete('/deleteFile', [FileController::class, 'eliminarArchivo']);

    });

});


