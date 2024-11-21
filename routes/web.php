<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CardController;
use App\Http\Controllers\ItemController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EditprofileController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



// Home
Route::redirect('/', '/home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/home', [HomeController::class, 'index']);


// Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/home/{id}', [ProductPageController::class, 'show'])->name('product.show');


Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'show')->name('home');
});
//Shopping Cart
Route::controller(ShoppingCartController::class)->group(function () {
    Route::get('/shoppingcart', 'show')->name('shoppingcart');
});
Route::post('/cart/add', [ShoppingCartController::class, 'add'])->name('cart.add');

Route::controller(WishlistController::class)->group(function () {
    Route::get('/wishlist', 'show')->name('wishlist');

});
Route::controller(ProductPageController::class)->group(function () {
    Route::get('/product', 'show')->name('product');
});

Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'profile')->name('profile');
});

Route::controller(ProfileController::class)->group(function () {
    Route::get('/editprofile', 'edit')->name('editprofile');
});

Route::post('/updateprofile', [ProfileController::class, 'update'])->name('updateprofile');

// Cards
Route::controller(CardController::class)->group(function () {
    Route::get('/cards', 'list')->name('cards');
    Route::get('/cards/{id}', 'show');
});


// API
Route::controller(CardController::class)->group(function () {
    Route::put('/api/cards', 'create');
    Route::delete('/api/cards/{card_id}', 'delete');
});

Route::controller(ItemController::class)->group(function () {
    Route::put('/api/cards/{card_id}', 'create');
    Route::post('/api/item/{id}', 'update');
    Route::delete('/api/item/{id}', 'delete');
});


// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});