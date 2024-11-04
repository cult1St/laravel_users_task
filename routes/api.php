<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get("/users/fetch_all", [UserController::class, "getAllUsers"]);
Route::post('/users/create_user', [UserController::class, 'createUser']);
Route::get('/users/products', [UserController::class, 'get_all_products']);
