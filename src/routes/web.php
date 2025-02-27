<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    route::get('/', [UserController::class, 'index']);
});
Route::get('/login', [UserController::class, 'login']);

Route::get('/mypage/profile', [UserController::class, 'profile']);