<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController as UserAuth;
use App\Http\Controllers\Store\AuthController as StoreAuth;
use App\Http\Controllers\User\TopController;
use App\Http\Controllers\User\SettingsController as UserSettingsController;
use App\Http\Controllers\Store\SettingsController as StoreSettingsController;

Route::view('/', 'welcome')->name('welcome');

Route::get('/test', fn () => view('pages.test'));

Route::get('/login', fn () => redirect()->route('user.login'))->name('login');

Route::prefix('user')->name('user.')->group(function () {
    Route::get('/login', [UserAuth::class, 'showLogin'])->name('login');
    Route::post('/login', [UserAuth::class, 'login'])->name('login.store');

    Route::get('/signup', [UserAuth::class, 'showSignup'])->name('signup');
    Route::post('/signup', [UserAuth::class, 'signup'])->name('signup.store');

    Route::get('/settings', [UserSettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [UserSettingsController::class, 'store'])->name('settings.store');

    Route::middleware('auth:user')->group(function () {
        Route::get('/top', function () {
            return view('pages.user.top'); })->name('top');
    });
});

Route::prefix('store')->name('store.')->group(function () {
    Route::get('/login', [StoreAuth::class, 'showLogin'])->name('login');
    Route::post('/login', [StoreAuth::class, 'login'])->name('login.store');

    Route::get('/signup', [StoreAuth::class, 'showSignup'])->name('signup');
    Route::post('/signup', [StoreAuth::class, 'signup'])->name('signup.store');

    Route::get('/settings', [StoreSettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [StoreSettingsController::class, 'store'])->name('settings.store');

    Route::middleware('auth:store')->group(function () {
        Route::get('/top', function () {
            return view('pages.store.top'); })->name('top');
    });
});

