<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FarmaPremiumController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/acumulate', [FarmaPremiumController::class, 'acumulate'])->name('acumulate');
Route::post('/canjear-points', [FarmaPremiumController::class, 'canjear'])->name('canjear');
Route::post('/review', [FarmaPremiumController::class, 'review'])->name('review');

