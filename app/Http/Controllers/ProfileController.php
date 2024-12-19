<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use App\Models\NotificationUser;
use App\Models\Notification;
use App\Models\PasswordResetToken;


class ProfileController extends Controller
{
    public function profile()
{
    $userId = auth()->id();

    // Fetch user's orders
    $orders = Order::where('user_id', $userId)->get();

    // Fetch user's notifications
    $notifications = NotificationUser::where('user_id', $userId)
        ->with('notification')
        ->get()
        ->pluck('notification');

    // Pass both orders and notifications to the view
    return view('pages/Profile', compact('orders', 'notifications'));
}

    public function edit()
    {
        return view('pages/Editprofile');
    }

    public function update(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'username' => 'required|string|max:50|unique:User,username,' . Auth::id(),
            'email' => 'required|string|email|max:75|unique:User,email,' . Auth::id(),
            'shipping_address' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed|regex:/[A-Z]/|regex:/[0-9]/',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Profile image validation
        ], [
            // Custom error messages for the password
            'password.min' => 'The password must be at least 8 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.regex' => 'The password must include at least one uppercase letter and one number.',
            'username.unique' => 'The username has already been taken.',
            'email.unique' => 'The email has already been taken.',
            'shipping_address.max' => 'The shipping address must not exceed 255 characters.',
            'image.image' => 'The profile picture must be an image.',
            'image.mimes' => 'The profile picture must be a file of type: jpeg, png, jpg.',
            'image.max' => 'The profile picture must not exceed 2MB.',

        ]);

        // Update the user's information
        $user = Auth::user();
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->shipping_address = $validated['shipping_address'];

        // Update the password only if a new one is provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        if ($request->hasFile('image')) {
            if ($user->image && file_exists(public_path('storage/' . $user->image))) {
                unlink(public_path('storage/' . $user->image));
            }
    
            // Store the new image
            $imagePath = $request->file('image')->store('userimages', 'public');
            $user->image = $imagePath;
        }

        $user->save();

        return redirect('/profile')->with('success', 'Profile updated successfully!');
    }

    public function deleteAccount()
    {
        $user = Auth::user();

        PasswordResetToken::where('email', $user->email)->delete();

        // Update the user's details to anonymize them
        $user->name = 'Anonymous' . $user->id;
        $user->username = 'anonymous'. $user->id;
        $user->email = 'anonymous' . $user->id . '@anonymous.com';
        $user->image = null; // Remove the profile picture
        $user->blocked = true; // Block the user

        $user->save();

        // Optionally log the user out after anonymizing
        Auth::logout();

        // Redirect to a page confirming the deletion, such as the home page
        return redirect('/')->with('success', 'Your account has been deleted and anonymized.');
    }
}
