<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Http\RedirectResponse;

class AdminLoginController extends Controller
{
    public function showLoginForm()
{
    if (Auth::guard('admin')->check()) {
        return redirect('/admin/dashboard');
    } elseif (Auth::guard('web')->check()) { 
        return redirect('/home'); 
    } else {
        return view('auth.admin/login'); 
    }
}

    public function authenticate(Request $request): RedirectResponse
{
    // Validate the input
    $request->validate([
        'email' => ['required', 'email'], 
        'password' => ['required'],
    ]);

    $credentials = [
        'email' => $request->email,
        'password' => $request->password,
    ];

    if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate(); 
        return redirect()->route('admin.dashboard.users'); 
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')
            ->withSuccess('You have logged out successfully!');
    } 
}
