<?php

use App\Http\Controllers\FacturaServicioPublicoController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([],401);
})->name('login');
        Route::group(['prefix' => 'v1'], function () {

   
});

