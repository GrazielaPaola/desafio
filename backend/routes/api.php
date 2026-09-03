<?php

use App\Http\Controllers\ColetaController;
use App\Http\Controllers\MotoristaController;
use App\Http\Controllers\ResumoController;
use Illuminate\Support\Facades\Route;

Route::get('resumo', ResumoController::class);
Route::get('coletas/exportar', [ColetaController::class, 'exportar']);
Route::apiResource('motoristas', MotoristaController::class);
Route::apiResource('coletas', ColetaController::class);
