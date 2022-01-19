<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// API endpoint for customer orders
Route::get('customers/{id}/orders', [CustomerController::class, 'orders']);
// API endpoint for the customers table
Route::apiResource('customers', CustomerController::class);

// API endpoint for the orders table
Route::apiResource('orders', OrderController::class);
