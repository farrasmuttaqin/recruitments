<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TodoController;
// use App\Http\Controllers\API\ItemController;

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

/**
 * Register & login
 */
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

/**
 * Get all Todo Data
 */
Route::get('todo', [TodoController::class, 'index']);

/**
 * Create Todo Data
 */
Route::post('todo', [TodoController::class, 'create']);

/**
 * Update Todo Data
 */
Route::put('todo/{todos_id}', [TodoController::class, 'update']);

/**
 * Delete Todo Data
 */
Route::delete('todo/{todos_id}', [TodoController::class, 'delete']);