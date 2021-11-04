<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FileProductController;

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
// Route::middleware('redvital')->group(function () {
    // orden de compras
    Route::get("/orders/{order}", [OrderController::class, 'show']);
    Route::post("/orders", [OrderController::class, 'store']);
    Route::post("/orders/{id}", [OrderController::class, 'update']);




    // Productos 
    Route::post("/products/lista", [ProductController::class, 'listProduct']);

// prueba
    Route::get("/prueba", [OrderController::class, 'prueba']);
    Route::get("/llamando", [OrderController::class, 'llamando']);
    // actualizacion de productos
    Route::post("/products/{id}", [ProductController::class, 'update']);
    // ordenes productos woocommerce
    Route::get("/woo-orders", [OrderController::class, 'index']);


    // File Products
    Route::get("/file-products", [FileProductController::class, 'index']);
    Route::post("/file-products", [FileProductController::class, 'store']);
    Route::get("/file-products/{file}", [FileProductController::class, 'show']);
    


// });

// Pruebas

Route::post("/fake_orders", [OrderController::class, 'fake_store']);
