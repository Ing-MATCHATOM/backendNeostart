<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\NiveauController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\TemoinController;
use Illuminate\Support\Facades\Route;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']); 
// backend/routes/api.php
Route::middleware('auth:sanctum')->group(function () {
Route::get('/parent/stats', [ParentController::class, 'getStats']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::controller(TemoinController::class)->prefix('temoin')->group(function(){
    Route::post('/store','store');
});
Route::controller(EleveController::class)->prefix('eleve')->group(function(){
    Route::post('/store','store');
});
Route::controller(EnseignantController::class)->prefix('enseignant')->group(function(){
    Route::post('/store','store');
});
});




Route::controller(NiveauController::class)->prefix('niveau')->group(function(){
    Route::get('/index','index');
});

