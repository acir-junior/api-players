<?php

use App\Http\Controllers\PlayerController;
use App\Http\Controllers\ShufflePlayersController;
use App\Http\Controllers\TeamsController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [PlayerController::class, 'index']);
Route::post('/players', [PlayerController::class, 'store'])->name('players.store');
Route::post('/players/confirm/{player}', [PlayerController::class, 'confirm'])->name('players.confirm');

