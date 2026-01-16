<?php

use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\GeneroController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::post('/auth/login', [UserController::class, 'login']);

// Rutas de catálogos (públicas)
Route::get('/departamentos', [DepartamentoController::class, 'index']);
Route::get('/tipo-documentos', [TipoDocumentoController::class, 'index']);
Route::get('/generos', [GeneroController::class, 'index']);
Route::get('/municipios', [MunicipioController::class, 'index']);

// Rutas protegidas (requieren autenticación JWT)
Route::middleware('jwt.auth')->group(function () {
    // Rutas de autenticación
    Route::post('/auth/logout', [UserController::class, 'logout']);
    Route::post('/auth/refresh', [UserController::class, 'refresh']);

    Route::apiResource('pacientes', PacienteController::class)->except(['create', 'edit']);
});
