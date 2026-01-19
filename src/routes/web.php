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
use App\Http\Controllers\User\StoreReviewController;
use App\Http\Controllers\User\StorePostController;
use App\Http\Controllers\User\StoreMenuController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\ReserveController;
use App\Http\Controllers\User\FavoriteController;
use App\Http\Controllers\User\FavoriteFolderController;
use App\Http\Controllers\User\MyCafeController;

use App\Http\Controllers\User\StoreController as UserStoreController;
use App\Http\Controllers\Store\StoreController as StoreStoreController;
use App\Http\Controllers\Store\StoreProfileController;
use App\Http\Controllers\Store\StoreImageController;


Route::view('/', 'welcome')->name('welcome');

Route::get('/test', fn () => view('pages.test'));

Route::get('/login', fn () => redirect()->route('user.login'))->name('login');

Route::prefix('user')->name('user.')->group(function () {
    Route::get('/login', [UserAuth::class, 'showLogin'])->name('login');
    Route::post('/login', [UserAuth::class, 'login'])->name('login.store');

    Route::get('/signup', [UserAuth::class, 'showSignup'])->name('signup');
    Route::post('/signup', [UserAuth::class, 'signup'])->name('signup.store');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');

    Route::middleware('auth:user')->group(function () {
        Route::get('/top',[TopController::class, 'index'])->name('top');
        Route::get('/recommended', [RecommendController::class, 'recommended'])->name('recommended');
        Route::get('/search',[SearchController::class,'index'])->name('search');
        Route::get('/stores/{store}',[StoreController::class,'show'])->name('stores.show');

        Route::post('/stores/{store}/reserve/confirm', [StoreController::class, 'reserveConfirmStore'])->name('stores.reserve.confirm.store');
        Route::get('/stores/{store}/reserve/confirm', [StoreController::class, 'reserveConfirm'])->name('stores.reserve.confirm');
        Route::post('/stores/{store}/reserve', [StoreController::class, 'reserveStore'])->name('stores.reserve.store');

        Route::get('/stores/{store}/reviews', [StoreReviewController::class, 'index'])->name('stores.reviews');
        Route::get('/stores/{store}/posts', [StorePostController::class, 'index'])->name('stores.posts');
        Route::get('/stores/{store}/menu', [StoreMenuController::class, 'show'])->name('stores.menu');

        Route::post('/stores/{store}/reviews', [ReviewController::class, 'store'])->name('stores.reviews.store');
        Route::get('/stores/{store}/reviews/create', [ReviewController::class, 'create'])->name('stores.reviews.create');
        Route::get('/stores/{store}/reviews/{review}', [StoreReviewController::class, 'show'])->name('stores.reviews.show');

        Route::get('/reserve', [ReserveController::class, 'index'])->name('reserve');
        Route::delete('/reserve/{reservation}', [ReserveController::class, 'destroy'])->name('reserve.destroy');

        Route::post('/stores/{store}/favorite', [FavoriteController::class, 'toggle'])->name('stores.favorite.toggle');
        Route::get('/stores/{store}/favorite/folders', [FavoriteFolderController::class, 'index'])->name('stores.favorite.folders.index');
        Route::post('/stores/{store}/favorite/folders', [FavoriteFolderController::class, 'sync'])->name('stores.favorite.folders.sync');
        Route::post('/favorite-folders', [FavoriteFolderController::class, 'store'])->name('favorite-folders.store');
        Route::delete('/favorite-folders/{favoriteFolder}', [FavoriteFolderController::class, 'destroy'])->name('favorite-folders.destroy');

        Route::get('/mycafe',[MyCafeController::class,'index'])->name('mycafe');
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

        Route::get('/profile',[StoreProfileController::class,'index'])->name('profile');
        Route::get('/profile/edit/basic',[StoreProfileController::class,'editBasic'])->name('profile.edit.basic');
        Route::patch('/profile/edit/basic',[StoreProfileController::class,'updateBasic'])->name('profile.update.basic');
        Route::get('/profile/edit/description',[StoreProfileController::class,'editDescription'])->name('profile.edit.description');
        Route::patch('/profile/edit/description',[StoreProfileController::class,'updateDescription'])->name('profile.update.description');
        Route::get('/profile/edit/contact',[StoreProfileController::class,'editContact'])->name('profile.edit.contact');
        Route::patch('/profile/edit/contact',[StoreProfileController::class,'updateContact'])->name('profile.update.contact');

        Route::get('/image',[StoreImageController::class,'index'])->name('image');
        Route::patch('/store/images/{image}/card', [StoreImageController::class, 'setCardImage'])->name('slide.card');
        Route::get('/image/edit/slide',[StoreImageController::class,'editSlide'])->name('image.edit.slide');
        Route::patch('/image/edit/slide',[StoreImageController::class,'updateSlide'])->name('image.update.slide');
    });
});

