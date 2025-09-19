<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\NiveauController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\TemoinController;
use App\Http\Controllers\SeanceController;
use App\Http\Controllers\RapportController;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées par Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout']);

    // Statistiques parent
    Route::get('/parent/stats', [ParentController::class, 'getStats']);

    // Routes Temoin
    Route::controller(TemoinController::class)->prefix('temoin')->group(function () {
        Route::get('/index','index');
        Route::post('/store','store');
    });

    // Routes Eleve
    Route::controller(EleveController::class)->prefix('eleve')->group(function () {
        Route::get('/index', 'index');   
        Route::post('/store', 'store');  
    });

    // Routes Enseignant
    Route::controller(EnseignantController::class)->prefix('enseignant')->group(function () {
        Route::get('/index','index');
        Route::post('/store','store');
    });

    // Routes Rapports
    Route::controller(RapportController::class)->prefix('rapports')->group(function () {
        Route::get('/', 'index');       
        Route::post('/', 'store');      
        Route::get('/{id}', 'show');    
    });

    // Routes Seance
    Route::controller(SeanceController::class)->prefix('emploi')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
    });

    // Routes Niveau
    Route::controller(NiveauController::class)->prefix('niveau')->group(function () {
        Route::get('/index','index');
    });
    Route::middleware('auth:sanctum')->group(function () {
    Route::get('/emploi', [SeanceController::class, 'index']);
    Route::post('/emploi', [SeanceController::class, 'store']);
});
});