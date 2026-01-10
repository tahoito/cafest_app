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
use App\Models\Store;


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

        Route::post('/stores/{store}/reserve/confirm', [StoreController::class, 'reserveConfirmStore'])->name('stores.reserve.confirm.store');
        Route::get('/stores/{store}/reserve/confirm', [StoreController::class, 'reserveConfirm'])->name('stores.reserve.confirm');
        Route::post('/stores/{store}/reserve', [StoreController::class, 'reserveStore'])->name('stores.reserve.store');

        Route::get('/stores/{store}/reviews', [StoreReviewController::class, 'index'])->name('stores.reviews');
        Route::get('/stores/{store}/posts', [StorePostController::class, 'index'])->name('stores.posts');

        Route::get('/stores/{store}/reviews/create', [ReviewController::class, 'create'])->name('stores.reviews.create');
        Route::post('/stores/{store}/reviews', [ReviewController::class, 'store'])->name('stores.reviews.store');
        
    });


Route::get('/user/reserve', function () {
$reservations = collect([
(object)[
'shopName' => 'CAFEST 名駅店',
'imageUrl' => 'https://placehold.co/600x400',
'date' => '2026-01-12',
'time' => '14:00',
'people' => 2,
],
(object)[
'shopName' => 'CAFEST 栄店',
'imageUrl' => 'https://placehold.co/600x400',
'date' => '2026-01-20',
'time' => null,
'people' => 1,
],
]);

return view('pages.user.reserve', compact('reservations'));
})->name('user.reserve');

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

