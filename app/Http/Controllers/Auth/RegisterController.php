<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\PasswordResetToken;

class RegisterController extends Controller
{
    /**
     * Display a login form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {


        $rules = [
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
            'username' => [
                'required',
                'string',
                'max:250',
                'unique:"User"'
            ],
            'email' => [
                'required',
                'email',
                'max:250',
                'unique:"User"'
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048', // Max file size in KB (2MB)
            ],

        ];

        $messages = [
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.regex' => 'The password must include at least one uppercase letter and one number.',
            'username.required' => 'The username field is required.',
            'username.unique' => 'The username has already been taken.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already an account associated with it.',
            'image.image' => 'The profile picture must be an image.',
            'image.mimes' => 'The profile picture must be a file of type: jpg, jpeg, png.',
            'image.max' => 'The profile picture may not be greater than 2MB.',
        ];

        $validated = $request->validate($rules, $messages);

        $profilePicturePath = null;

        // Handle profile picture upload
        if ($request->hasFile('image')) {
            $profilePicturePath = $request->file('image')->store('userimages', 'public');
        }

        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image' => $profilePicturePath,
        ]);
        $token = Str::random(60);  
        PasswordResetToken::create([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();

        $this->migrateSessionCartToDatabase($user);

    
        return redirect()->route('home')
            ->withSuccess('You have successfully registered & logged in!');
    }


    /**
     * Migrate session cart items to the database cart for a user.
     */
    private function migrateSessionCartToDatabase($user)
    {
        $sessionCart = session('cart', []);

        foreach ($sessionCart as $item) {
            // Check if product exists in the database
            $product = \App\Models\Product::find($item['product_id']);
            if (!$product) {
                continue; // Skip if the product no longer exists
            }

            // Check if the product is already in the user's cart
            $existingCartItem = \App\Models\ShoppingCart::where('user_id', $user->id)
                ->where('product_id', $item['product_id'])
                ->first();

            if ($existingCartItem) {
                // Update quantity if the product is already in the cart
                $existingCartItem->quantity += $item['quantity'];
                $existingCartItem->save();
            } else {
                // Add a new cart item
                \App\Models\ShoppingCart::create([
                    'user_id' => $user->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        // Clear the session cart after migration
        session()->forget('cart');
    }
}
