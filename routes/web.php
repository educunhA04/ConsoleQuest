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
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
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
    Route::get('/filtered', 'index')->name('home.filtered');

});

Route::get('/aboutus', [HomeController::class, 'aboutus'])->name('home.aboutus');
Route::get('/help', [HomeController::class, 'help'])->name('home.help');
Route::get('/faqs', [HomeController::class, 'faqs'])->name('home.faqs');
Route::get('/terms', [HomeController::class, 'terms'])->name('home.terms');



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


// Review
Route::middleware(['auth'])->group(function () {
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

Route::get('/products/{id}/reviews', [ReviewController::class, 'index'])->name('reviews.index');



// Wishlist
Route::controller(WishlistController::class)->group(function () {
    Route::get('/wishlist', 'show')
        ->name('wishlist.show')
        ->middleware('can:view,App\Models\Wishlist');

    Route::post('/wishlist/add', 'add')
        ->name('wishlist.add')
        ->middleware('can:create,App\Models\Wishlist');

        Route::delete('/wishlist/remove/{id}', [WishlistController::class, 'remove'])
            ->name('wishlist.remove')
            ->middleware('auth');

    
    
});


// Profile
Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile', 'profile')->name('profile.show');
    Route::get('/editprofile', 'edit')->name('profile.edit');
    Route::post('/updateprofile', 'update')->name('profile.update');
    Route::delete('/deleteaccount', 'deleteAccount')->name('deleteAccount');
});


// Checkout
Route::controller(CheckoutController::class)->group(function () {
    Route::get('/checkout', 'checkout')->name('checkout.show');
    Route::post('/checkout/finalize', 'finalize')->name('checkout.finalize');
});

//Notification
Route::controller(NotificationController::class)->group(function () {
    Route::get('/notifications', 'index')->name('notifications.index');
    Route::get('/notifications/{id}', 'show')->name('notifications.show');
    Route::post('/notifications/{notification}/view', 'markAsViewed')->name('notifications.view');
});



//Order
Route::middleware(['auth'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderId}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{orderId}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
});

//report
Route::controller(ReportController::class)->group(function () {
    Route::post('/report/{id}', 'report')->name('reviews.report');
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
// Admin Authentication Routes
// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});

// Admin Routes (Authenticated)
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    // Dashboard
    Route::get('/dashboard/users', [AdminController::class, 'show'])->name('dashboard.users');
    Route::post('/dashboard/users', [AdminController::class, 'showFiltredUsers'])->name('dashboard.users.filtered');
    Route::get('/dashboard/products', [AdminController::class, 'showProducts'])->name('dashboard.products');
    Route::get('/dashboard/reports', [AdminController::class, 'showReports'])->name('dashboard.reports');


    // View and Manage Users and Products
    Route::post('/view-user', [AdminController::class, 'viewUser'])->name('viewUser');
    Route::get('/view-product/{id}', [AdminController::class, 'viewProduct'])->name('viewProduct');
    
    Route::post('/block-user/{id}', [AdminController::class, 'blockUser'])->name('user.block');
    Route::post('/unblock-user/{id}', [AdminController::class, 'unblockUser'])->name('user.unblock');
    Route::post('/change-user', [AdminController::class, 'changeUser'])->name('user.change');
    Route::post('/change-product', [AdminController::class, 'changeProduct'])->name('changeProduct'); // é aqui que se há a notificaçao tmb
    Route::get('/create-user', [AdminController::class, 'createUserShow'])->name('user.create');
    Route::post('/store-user', [AdminController::class, 'storeUser'])->name('user.store');
    Route::post('/update-profile', [AdminController::class, 'update'])->name('updateProfile');
    Route::get('/create-user', [AdminController::class, 'createUserShow'])->name('createUser');
    Route::get('/create_product', [AdminController::class, 'createProductShow'])->name('createProduct');
    Route::post('/store-product', [AdminController::class, 'storeProduct'])->name('storeProduct');

    // User Orders Management
    Route::get('/user/{id}/orders', [AdminController::class, 'viewUserOrders'])->name('user.orders');
    Route::get('/orders/{orderId}', [AdminController::class, 'viewOrderDetails'])->name('order.details');

    // Reports Management
    Route::put('/reports/{id}/handle', [AdminController::class, 'handleReport'])->name('handleReport');
    Route::delete('/reports/{id}', [AdminController::class, 'deleteReport'])->name('deleteReport');

    // Reviews
    Route::get('/review/{id}', [AdminController::class, 'viewReview'])->name('viewReview');
    Route::delete('/reviews/{id}', [AdminController::class, 'deleteReview'])->name('reviews.destroy');

    Route::Put('/orders/{id}', [AdminController::class, 'updateStatus'])->name('orders.updateStatus');

    //Type
    Route::post('/addType', [AdminController::class, 'addType'])->name('addType');

});


Route::delete('/admin/user/delete', [AdminController::class, 'deleteUser'])->name('admin.user.delete');

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register')->name('register.store');
});
