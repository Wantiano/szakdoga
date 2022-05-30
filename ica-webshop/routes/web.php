<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function() {
    Route::get('/home', 'index')->name('home');
    Route::get('/', 'index');
    Route::get('search/', 'search')->name('search');
    Route::get('aboutus', 'aboutus')->name('aboutus');
});


Route::controller(RegisterController::class)->group(function() {
    Route::get('register', 'showRegistrationForm')->name('register');
    Route::post('register', 'register');
});

Route::controller(LoginController::class)->group(function() {
    Route::get('login', 'showLoginForm')->name('login');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->name('logout');
});

Route::controller(UserController::class)->group(function() {
    Route::get('profile', 'show')->name('users.show');
    Route::post('profile/update', 'update')->name('users.update');
});

Route::controller(FavoriteController::class)->group(function() {
    Route::get('favorites', 'index')->name('favorites');
    Route::post('favorites/store/{id}', 'store')->name('favorites.store');
    Route::delete('favorites/destroy/{id}', 'destroy')->name('favorites.destroy');
});

Route::controller(CartController::class)->group(function() {
    Route::get('cart', 'index')->name('cart.index');
    Route::post('cart/store/{id}', 'store')->name('cart.store');
    Route::delete('cart/{id}', 'destroy')->name('cart.destroy');
    Route::post('cart/decrease/{id}', 'decrease')->name('cart.decrease');
    Route::post('cart/increase/{id}', 'increase')->name('cart.increase');
    Route::post('cart/update/{id}', 'update')->name('cart.update');
});

Route::resource('categories', CategoryController::class);

Route::resource('products', ProductController::class);

Route::controller(OrderController::class)->group(function() {
    Route::get('orders/{id}', 'show')->name('orders.show');
    Route::get('check', 'create')->name('orders.check');
    Route::post('orders/store', 'store')->name('orders.store');
    Route::get('orders/{id}/edit', 'edit')->name('orders.edit');
    Route::patch('orders/{id}/update', 'update')->name('orders.update');

    Route::get('myorders', 'myorders')->name('orders.myorders');
    Route::get('allorders', 'allorders')->name('orders.allorders');

    Route::get('methods', 'methods')->name('orders.methods');
    Route::patch('methods/update', 'updateMethods')->name('orders.update.methods');

    Route::get('data', 'data')->name('orders.data');
    Route::patch('data/update', 'updateData')->name('orders.update.data');
});