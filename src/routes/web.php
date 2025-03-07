<?php


use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

// **商品一覧ページ**
Route::get('/', [ItemController::class, 'index'])->name('home');

Route::get('/mylist', [ItemController::class, 'mylist'])->name('mylist');

Route::get('/search', [ItemController::class, 'search'])->name('search');


Route::middleware('auth')->group(function () {
    Route::get('/mypage', [UserController::class, 'showUser'])->name('mypage.index');
    route::get('/mypage/profile', [UserController::class, 'edit'])->name('mypage.profile');
    Route::post('/mypage/profile', [UserController::class, 'update'])->name('mypage.profile.update');


});

