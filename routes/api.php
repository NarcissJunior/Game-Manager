<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CharacterController;
use App\Http\Controllers\BattleController;
use App\Http\Controllers\LeaderboardController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/character/create', [CharacterController::class, 'create']);
Route::get('/character/show', [CharacterController::class, 'show']);
Route::post('/battle', [BattleController::class, 'battle']);
Route::get('/leaderboard', [LeaderboardController::class, 'show']);


