<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\MatchController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\PlayerController;

Route::get('/matches', [MatchController::class, 'index']); // Obtener todos los partidos
Route::post('/matches', [MatchController::class, 'store']); // Crear un nuevo partido
Route::get('/matches/{id}', [MatchController::class, 'show']); // Obtener un partido por ID
Route::put('/matches/{id}', [MatchController::class, 'update']); // Actualizar un partido
Route::delete('/matches/{id}', [MatchController::class, 'destroy']); // Eliminar un partido



Route::get('/tournaments', [TournamentController::class, 'index']); // Obtener todos los torneos
Route::post('/tournaments', [TournamentController::class, 'store']); // Crear un nuevo torneo
Route::get('/tournaments/{id}', [TournamentController::class, 'show']); // Obtener un torneo por ID
Route::put('/tournaments/{id}', [TournamentController::class, 'update']); // Actualizar un torneo
Route::delete('/tournaments/{id}', [TournamentController::class, 'destroy']); // Eliminar un torneo
Route::get('/tournaments/{id}/start', [TournamentController::class, 'startTournament']); // Iniciar un torneo



Route::get('/players', [PlayerController::class, 'index']); // Obtener todos los jugadores
Route::post('/players', [PlayerController::class, 'store']); // Crear un nuevo jugador
Route::get('/players/{id}', [PlayerController::class, 'show']); // Obtener un jugador por ID
Route::put('/players/{id}', [PlayerController::class, 'update']); // Actualizar un jugador
Route::delete('/players/{id}', [PlayerController::class, 'destroy']); // Eliminar un jugador
