<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AdminLoginController;
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
use App\Http\Controllers\AdminController;



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
Route::post('/home', [HomeController::class, 'index'])->name('home');
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



// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate')->name('login.authenticate');
    Route::get('/logout', 'logout')->name('logout');
});
//admin
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'show'])->name('dashboard');
    Route::post('/dashboard', [AdminController::class, 'showFiltredUsers'])->name('dashboard');
    Route::post('/view-user', [AdminController::class, 'viewUser'])->name('viewUser');
    Route::post('/change-user', [AdminController::class, 'changeUser'])->name('changeUser');
    Route::post('/update-profile', [AdminController::class, 'update'])->name('updateProfile');

});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register')->name('register.store');
});
