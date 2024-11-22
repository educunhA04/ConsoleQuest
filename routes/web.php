<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EditprofileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\WishlistController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Aqui estão as rotas para sua aplicação. Elas são carregadas pelo
| RouteServiceProvider e pertencem ao grupo "web".
|--------------------------------------------------------------------------
*/

// Home
Route::redirect('/', '/home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'show')->name('home');
    Route::get('/controllers', 'showControllers')->name('home.controllers');
    Route::get('/games', 'showGames')->name('home.games');
    Route::get('/consoles', 'showConsoles')->name('home.consoles');
});

// Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/home/{id}', [ProductPageController::class, 'show'])->name('product.show');

// Shopping Cart
Route::controller(ShoppingCartController::class)->group(function () {
    Route::get('/shoppingcart', 'show')->name('cart.show');
    Route::post('/cart/add', 'add')->name('cart.add');
    Route::post('/cart/update', 'update')->name('cart.update');
    Route::post('/cart/remove', 'remove')->name('cart.remove');
    Route::post('/cart/clear', 'clear')->name('cart.clear');
    Route::get('/cart/checkout', 'checkout')->name('cart.checkout');
});

// Wishlist
Route::controller(WishlistController::class)->group(function () {
    Route::get('/wishlist', 'show')->name('wishlist.show');
    Route::post('/wishlist/add', 'add')->name('wishlist.add');
});

// Profile
Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'profile')->name('profile.show');
    Route::get('/editprofile', 'edit')->name('profile.edit');
    Route::post('/updateprofile', 'update')->name('profile.update');
});

// Checkout
Route::controller(CheckoutController::class)->group(function () {
    Route::get('/checkout', 'checkout')->name('checkout.show');
    Route::post('/checkout/finalize', 'finalize')->name('checkout.finalize');
});

// Cards
Route::controller(CardController::class)->group(function () {
    Route::get('/cards', 'list')->name('cards.list');
    Route::get('/cards/{id}', 'show')->name('cards.show');
});

// API (Cards and Items)
Route::prefix('api')->group(function () {
    Route::controller(CardController::class)->group(function () {
        Route::put('/cards', 'create')->name('api.cards.create');
        Route::delete('/cards/{card_id}', 'delete')->name('api.cards.delete');
    });

    Route::controller(ItemController::class)->group(function () {
        Route::put('/cards/{card_id}', 'create')->name('api.items.create');
        Route::post('/item/{id}', 'update')->name('api.items.update');
        Route::delete('/item/{id}', 'delete')->name('api.items.delete');
    });
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate')->name('login.authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register')->name('register.store');
});
