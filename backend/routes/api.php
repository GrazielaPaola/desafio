<?php

use App\Http\Controllers\ColetaController;
use App\Http\Controllers\MotoristaController;
use Illuminate\Support\Facades\Route;

Route::apiResource('motoristas', MotoristaController::class);
Route::apiResource('coletas', ColetaController::class);
