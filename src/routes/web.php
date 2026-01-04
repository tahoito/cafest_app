<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController as UserAuth;
use App\Http\Controllers\Store\AuthController as StoreAuth;
use App\Http\Controllers\User\TopController;
use App\Http\Controllers\User\SettingsController as UserSettingsController;
use App\Http\Controllers\Store\SettingsController as StoreSettingsController;
use App\Http\Controllers\User\RecommendController;
use App\Http\Controllers\User\SearchController;
use App\Http\Controllers\User\StoreController;


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
        Route::get('/top',[TopController::class, 'index'])->name('top');
        Route::get('/recommended', [RecommendController::class, 'recommended'])->name('recommended');
        Route::get('/search',[SearchController::class,'index'])->name('search');
        Route::get('/stores/{store}',[StoreController::class,'show'])->name('stores.show');

        Route::post('/stores/{store}/reserve/confirm', [StoreController::class, 'reserveConfirm'])->name('stores.reserve.confirm');
        Route::post('/stores/{store}/reserve', [StoreController::class, 'reserveStore'])->name('stores.reserve.store');
    });


    Route::view('/reserve', 'pages.user.reserve')->name('reserve');

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

