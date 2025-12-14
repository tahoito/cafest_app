<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', fn () => view('pages.test'));

Route::prefix('user')->group(function () {
    Route::get('/login', fn () => view('pages.user.auth.login'))->name('user.login');
    Route::get('/signup', fn () => view('pages.user.auth.signup'))->name('user.signup');
});

Route::prefix('store')->group(function () {
    Route::get('/login', fn () => view('pages.store.auth.login'))->name('store.login');
    Route::get('/signup', fn () => view('pages.store.auth.signup'))->name('store.signup');
});
