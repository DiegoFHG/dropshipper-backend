<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ProductController;
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
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::delete('logout', [AuthController::class, 'logout']);

Route::get('products', [ProductController::class, 'index']);
Route::get('sellers/{id}/relationships/products', [ProductController::class, 'indexBySellerPublic']);
Route::post('products/search', [ProductController::class, 'searchPublic']);

Route::middleware('auth:sanctum')->group(function () {
  Route::get('me', function (Request $req) {
    return $req->user();
  });
  Route::get('me/sellers', [SellerController::class, 'show']);
  Route::get('me/sellers/relationships/products', [ProductController::class, 'indexBySeller']);

  Route::post('sellers', [SellerController::class, 'store']);
  Route::post('products', [ProductController::class, 'store']);
  Route::patch('products/{id}', [ProductController::class, 'update']);
  Route::delete('products/{id}', [ProductController::class, 'destroy']);
});
