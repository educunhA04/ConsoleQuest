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
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
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
Route::post('/home', [HomeController::class, 'index'])->name('home.index');
Route::controller(HomeController::class)->group(function () {
    Route::get('/home', 'show')->name('home');
    Route::get('/controllers', 'showControllers')->name('home.controllers');
    Route::get('/games', 'showGames')->name('home.games');
    Route::get('/consoles', 'showConsoles')->name('home.consoles');
});

Route::get('/aboutus', [HomeController::class, 'aboutus'])->name('home.aboutus');

Route::get('/help', [HomeController::class, 'help'])->name('home.help');

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

//Order
Route::controller(OrderController::class)->group(function () {
    Route::get('/orders', 'index')->name('orders.index');
    Route::get('/orders/{orderId}', 'show')->name('orders.show');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate')->name('login.authenticate');
    Route::get('/logout', 'logout')->name('logout');
    Route::get('password/reset', 'showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'sendResetLinkEmail')->name('password.email');

});
Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);
 
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));
 
            $user->save();
 
            event(new PasswordReset($user));
        }
    );
 
    return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

//admin
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard/users', [AdminController::class, 'show'])->name('dashboard.users');
    Route::post('/dashboard/users', [AdminController::class, 'showFiltredUsers'])->name('dashboard.users.filtred');
    Route::get('/dashboard/products', [AdminController::class, 'showProducts'])->name('dashboard.products');
    Route::post('/view-user', [AdminController::class, 'viewUser'])->name('viewUser');
    Route::get('/view-product/{id}', [AdminController::class, 'viewProduct'])->name('viewProduct');
    Route::post('/change-user', [AdminController::class, 'changeUser'])->name('changeUser');
    Route::post('/change-product', [AdminController::class, 'changeProduct'])->name('changeProduct');
    Route::post('/update-profile', [AdminController::class, 'update'])->name('updateProfile');
    Route::get('/create-user', [AdminController::class, 'createUserShow'])->name('createUser');
    Route::get('/create_product', [AdminController::class, 'createProductShow'])->name('createProduct');
    Route::post('/store-user', [AdminController::class, 'storeUser'])->name('storeUser');
    Route::post('/store-product', [AdminController::class, 'storeProduct'])->name('storeProduct');

});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register')->name('register.store');
});
