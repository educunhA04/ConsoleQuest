<?php
 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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
        return redirect('/admin/dashboard'); 
    } 
    else {
        return view('auth.login');
    }
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
        $request->session()->regenerate();
        return redirect()->route('home'); // Redirect to desired route
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
