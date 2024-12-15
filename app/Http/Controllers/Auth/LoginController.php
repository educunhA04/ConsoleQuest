<?php
 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

use Illuminate\View\View;

class LoginController extends Controller
{

    /**
     * Display a login form.
     */
    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect('/home');
        } 
        elseif (Auth::guard('admin')->check()) {
            return redirect('/admin/dashboard/users'); 
        } 
        else {
            return view('auth.login');
        }
    }
    public function showLinkRequestForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect('/home');
        } 
        elseif (Auth::guard('admin')->check()) {
            return redirect('/admin/dashboard/users'); 
        } 
        else {
            return view('auth.recoverPass');
        }
    }
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:User,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
{
    // Validate the input
    $request->validate([
        'login' => ['required'], // Use a common field name for both email and username
        'password' => ['required'],
    ]);

    // Check if the input is an email or username
    $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    // Retrieve the credentials based on email or username
    $credentials = [
        $field => $request->login,
        'password' => $request->password,
    ];

    // Attempt to authenticate with the credentials
    if (Auth::attempt($credentials, $request->filled('remember'))) {
        $user = Auth::user();
    
        if ($user->blocked) {
            Auth::logout();
            return back()->withErrors(['login' => 'Your account has been blocked. Please contact support.']);
        }
    
        $request->session()->regenerate();
    
        // Mesclar itens do carrinho
        $sessionCart = session('cart', []);
        foreach ($sessionCart as $productId => $item) {
            $cartItem = \App\Models\ShoppingCart::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->first();
    
            if ($cartItem) {
                $cartItem->increment('quantity', $item['quantity']);
            } else {
                \App\Models\ShoppingCart::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                ]);
            }
        }
        session()->forget('cart'); // Limpar o carrinho da sessão
    
        return redirect()->route('home'); // Redirecionar o usuário
    }
    
    // Return back with an error if authentication fails
    return back()->withErrors([
        'login' => 'The provided credentials do not match our records.',
    ])->onlyInput('login');
}


    /**
     * Log out the user from application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')
            ->withSuccess('You have logged out successfully!');
    } 
}
