<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController as UserAuth;
use App\Http\Controllers\Store\AuthController as StoreAuth;
use App\Http\Controllers\User\TopController;

Route::view('/', 'welcome')->name('welcome');

Route::get('/test', fn () => view('pages.test'));

Route::prefix('user')->name('user.')->group(function () {
    Route::get('/login', [UserAuth::class, 'showLogin'])->name('login');
    Route::post('/login', [UserAuth::class, 'login'])->name('login.store');

    Route::get('/signup', [UserAuth::class, 'showSignup'])->name('signup');
    Route::post('/signup', [UserAuth::class, 'signup'])->name('signup.store');
});

Route::prefix('store')->name('store.')->group(function () {
    Route::get('/login', [StoreAuth::class, 'showLogin'])->name('login');
    Route::post('/login', [StoreAuth::class, 'login'])->name('login.store');

    Route::get('/signup', [StoreAuth::class, 'showSignup'])->name('signup');
    Route::post('/signup', [StoreAuth::class, 'signup'])->name('signup.store');
});

Route::middleware('auth:user')->group(function () {
    Route::get('/top', [TopController::class, 'index'])->name('top');
});