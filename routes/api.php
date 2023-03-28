<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources([
    'stores'     => \App\Http\Controllers\StoreController::class,
    'products'   => \App\Http\Controllers\ProductController::class,
    'carts'      => \App\Http\Controllers\CartController::class,
    'cart-items' => \App\Http\Controllers\CartItemController::class,
]);

Route::post('products/{product}/attach-stores', [\App\Http\Controllers\ProductController::class, 'attachStores'])->name('products.attach-stores');
