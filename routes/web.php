<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Auth;
/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
Route::get('/', [LinkController::class, 'index']);
Route::get('/k/{key}', [LinkController::class, 'update']);
Route::middleware('auth')->group(function () {
    Route::post('/save', [LinkController::class, 'save']);    
});

require __DIR__ . '/auth.php';
