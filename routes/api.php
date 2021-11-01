<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderProductController;
use App\Http\Controllers\ProductController;

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

Route::middleware('redvital')->get('/user', function (Request $request) {
    return $request->user();
});

// API
Route::middleware('redvital')->group(function(){
    // orden de compras
    Route::get("/orders/{order}", [OrderController::class, 'show']);
    Route::post("/orders", [OrderController::class, 'store']);
    Route::post("/orders/{id}", [OrderController::class, 'update']);
    // actualizacion de productos
    Route::post("/products/{id}", [ProductController::class, 'update']);   
    // ordenes productos woocommerce
    Route::get("/woo_orders", [OrderController::class, 'index']);

});
 

// Pruebas 

Route::post("/fake_orders", [OrderController::class, 'fake_store']);