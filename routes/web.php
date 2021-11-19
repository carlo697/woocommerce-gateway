<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\web\ProductoStoreController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();



// listar productos del wgateway

Route::get("/", [ProductoStoreController::class, 'index'])->name('products.index');
Route::post("/", [ProductoStoreController::class, 'index'])->name('products.index');
// Search products
Route::get('searchProduct', [ProductoStoreController::class, 'search'] )->name('producto.search');

