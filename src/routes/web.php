<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;


Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/search', [ItemController::class, 'search'])->name('search');

Route::get('/item/{item_id}', [ItemController::class, 'showItem'])->name('item.show');

Route::middleware('auth')->group(function () {
    Route::get('/mypage', [UserController::class, 'showUser'])->name('mypage');
    route::get('/mypage/profile', [UserController::class, 'edit'])->name('mypage.profile');
    Route::patch('/mypage/profile', [UserController::class, 'update'])->name('mypage.profile.update');

    Route::get('/purchase/address/{item_id}', [UserController::class, 'editAddress'])->name('address.edit');
    Route::post('/purchase/address/{item_id}', [UserController::class, 'updateAddress'])->name('address.update');

    Route::get('/purchase/{item_id}', [ItemController::class, 'purchase'])->name('purchase.index');
    Route::post('/purchase/{item_id}', [ItemController::class, 'storePurchase'])->name('purchase.store');

    Route::post('/like/{item}', [ItemController::class, 'toggle'])->name('like.toggle');
    Route::post('/item/{item_id}/comment', [ItemController::class, 'store'])->name('comment.store');

    Route::get('/sell', [ItemController::class, 'create'])->name('sell.create');
    Route::post('/sell', [ItemController::class, 'sellStore'])->name('sell.store');
});

