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

    $orders = Order::where('user_id', $userId)->orderBy('id','desc')->get();

    $notifications = NotificationUser::where('user_id', $userId)
        ->with(['notification' => function ($query) {
            $query->orderBy('id', 'desc');
        }])
        ->get()
        ->pluck('notification')
        ->SortByDesc('id');

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
        'address' => 'max:255',
        'postal_code' => 'max:20',
        'location' => 'max:100',
        'country' => 'max:100',
        'password' => 'nullable|string|min:8|confirmed|regex:/[A-Z]/|regex:/[0-9]/',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Profile image validation
    ], [
        // Custom error messages
        'password.min' => 'The password must be at least 8 characters long.',
        'password.confirmed' => 'The password confirmation does not match.',
        'password.regex' => 'The password must include at least one uppercase letter and one number.',
        'username.unique' => 'The username has already been taken.',
        'email.unique' => 'The email has already been taken.',
        'image.image' => 'The profile picture must be an image.',
        'image.mimes' => 'The profile picture must be a file of type: jpeg, png, jpg.',
        'image.max' => 'The profile picture must not exceed 2MB.',
    ]);

    // Update the user's information
    $user = Auth::user();
    $user->name = $validated['name'];
    $user->username = $validated['username'];
    $user->email = $validated['email'];

    // Update the password only if a new one is provided
    if (!empty($validated['password'])) {
        $user->password = Hash::make($validated['password']);
    }

    // Handle profile image update
    if ($request->hasFile('image')) {
        if ($user->image && file_exists(public_path('storage/' . $user->image))) {
            unlink(public_path('storage/' . $user->image));
        }

        // Store the new image
        $imagePath = $request->file('image')->store('userimages', 'public');
        $user->image = $imagePath;
    }

    $user->save();

    // Update or create the shipping address
    $shippingAddress = $user->shippingAddress()->firstOrNew();

    // Update each field only if provided
    if ($request->filled(['address', 'postal_code', 'location', 'country'])) {
        $shippingAddress = $user->shippingAddress()->firstOrNew();
        $shippingAddress->address = $validated['address'];
        $shippingAddress->postal_code = $validated['postal_code'];
        $shippingAddress->location = $validated['location'];
        $shippingAddress->country = $validated['country'];
        $shippingAddress->save();
    }


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
