<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\NiveauController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\TemoinController;
use App\Http\Controllers\SeanceController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\AssociationController;
use Illuminate\Support\Facades\Route;

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
<<<<<<< HEAD

// Routes protégées par Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout']);

    // Statistiques parent
    Route::get('/parent/stats', [ParentController::class, 'getStats']);
=======
Route::post('/logout', [AuthController::class, 'logout']);
// backend/routes/api.php
Route::middleware('auth:sanctum')->group(function () {
Route::get('/parent/stats', [ParentController::class, 'getStats']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::controller(TemoinController::class)->prefix('temoin')->group(function(){
    Route::get('/index','index');
    Route::post('/store','store');
    Route::get('/','getParentTemoin');

});
Route::controller(EleveController::class)->prefix('eleve')->group(function(){
    Route::get('/index', 'index');   // Liste des élèves
    Route::post('/store', 'store');  // Création d'un élève
      Route::get('/', 'getParentEleve'); // Récupérer les élèves d'un parent

});
Route::controller(EnseignantController::class)->prefix('enseignant')->group(function(){
    Route::get('/index','index');
    Route::post('/store','store');

});

Route::controller(RapportController::class)->prefix('rapports')->group(function () {
    Route::get('/', 'index');        // Liste des rapports de l'enseignant connecté
    Route::post('/', 'store');       // Enregistrer un rapport
    Route::get('/{id}', 'show');     // Détail d’un rapport
});

Route::controller(AssociationController::class)
    ->prefix('associations')
    ->group(function () {
        Route::post('/', 'store');
    });

});
>>>>>>> bbda43f1888c2a244948e96466ad64f64f47c6c2

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
<<<<<<< HEAD
});
=======
>>>>>>> bbda43f1888c2a244948e96466ad64f64f47c6c2
