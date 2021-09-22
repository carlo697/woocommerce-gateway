<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderProductController;

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

Route::get("/prueba", function(Request $request) {
    return DB::connection('woocommerce')->table('wp_posts')->where('post_type', 'product')->orwhere('post_type', 'product_variation')->get();
});


Route::get("/order", [OrderController::class, 'index']);


Route::get("/product_order", [OrderProductController::class, 'index']);


Route::get("/customer", [CustomerController::class, 'index']);