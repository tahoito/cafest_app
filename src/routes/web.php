<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController as UserAuth;
use App\Http\Controllers\Store\AuthController as StoreAuth;
use App\Http\Controllers\User\TopController;
use App\Http\Controllers\User\SettingsController as UserSettingsController;
use App\Http\Controllers\Store\SettingsController as StoreSettingsController;
use App\Http\Controllers\User\RecommendController;
use App\Http\Controllers\User\SearchController;
use App\Http\Controllers\User\StoreReviewController;
use App\Http\Controllers\User\StorePostController;
use App\Http\Controllers\User\StoreMenuController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\ReserveController;
use App\Http\Controllers\User\FavoriteController;
use App\Http\Controllers\User\FavoriteFolderController;

use App\Http\Controllers\User\StoreController as UserStoreController;
use App\Http\Controllers\Store\StoreController as StoreStoreController;



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
        Route::get('/stores/{store}',[UserStoreController::class,'show'])->name('stores.show');

        Route::post('/stores/{store}/reserve/confirm', [UserStoreController::class, 'reserveConfirmStore'])->name('stores.reserve.confirm.store');
        Route::get('/stores/{store}/reserve/confirm', [UserStoreController::class, 'reserveConfirm'])->name('stores.reserve.confirm');
        Route::post('/stores/{store}/reserve', [UserStoreController::class, 'reserveStore'])->name('stores.reserve.store');

        Route::get('/stores/{store}/reviews', [UserStoreReviewController::class, 'index'])->name('stores.reviews');
        Route::get('/stores/{store}/posts', [UserStorePostController::class, 'index'])->name('stores.posts');
        Route::get('/stores/{store}/menu', [UserStoreMenuController::class, 'show'])->name('stores.menu');

        Route::get('/stores/{store}/reviews/create', [UserReviewController::class, 'create'])->name('stores.reviews.create');
        Route::post('/stores/{store}/reviews', [UserReviewController::class, 'store'])->name('stores.reviews.store');

        Route::get('/reserve', [ReserveController::class, 'index'])->name('reserve');
        Route::delete('/reserve/{reservation}', [ReserveController::class, 'destroy'])->name('reserve.destroy');

        Route::post('/stores/{store}/favorite', [FavoriteController::class, 'toggle'])->name('stores.favorite.toggle');
        Route::get('/stores/{store}/favorite/folders', [FavoriteFolderController::class, 'index'])->name('stores.favorite.folders.index');
        Route::post('/stores/{store}/favorite/folders', [FavoriteFolderController::class, 'sync'])->name('stores.favorite.folders.sync');
        Route::post('/favorite-folders', [FavoriteFolderController::class, 'store'])->name('favorite-folders.store');
        Route::delete('/favorite-folders/{favoriteFolder}', [FavoriteFolderController::class, 'destroy'])->name('favorite-folders.destroy');
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
        Route::get('/top', [StoreStoreController::class, 'index'])->name('top');
        Route::post('/toggle-public', [StoreStoreController::class, 'togglePublic'])->name('toggle-public');
    });
});

