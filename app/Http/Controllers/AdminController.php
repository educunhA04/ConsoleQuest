<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Admin;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
  
    public function show(): View
    {

        $users = User::orderBy('id')->get();
        return view('pages.admin/dashboard',['users' => $users]);
    }
    public function showProducts(): View
    {

        $products = Product::orderBy('id')->get();
        return view('pages.admin/dashboard',['products' => $products]);
    }
    public function viewUser(Request $request): View
    {
        $user = User::findOrFail($request->input('user_id')); 
        return view('pages.admin/viewUser', ['user' => $user]);
    }
    public function viewProduct($id)
    {
    $product = Product::findOrFail($id); 
    return view('pages.admin/viewProduct', compact('product'));
    }

    public function changeUser(Request $request): View
    {
        $user = User::findOrFail($request->input('user_id')); 
        return view('pages.admin/changeUser', ['user' => $user]);
    }
    public function showFiltredUsers(Request $request): View
    {
        $query = $request->input('query', '');
        $sanitizedQuery = strtolower(trim($query)); // Converte para minúsculas e remove espaços extras
        $queryNoSpaces = str_replace(' ', '', $sanitizedQuery); // Remove todos os espaços

        $users = User::query();
        if ($sanitizedQuery) {
            $users = $users->whereRaw('LOWER(REPLACE(name, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"])
                ->orWhereRaw('LOWER(REPLACE(username, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"])
                ->orWhereRaw('LOWER(REPLACE(email, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"]);
                
        }
        $users = $users->get();


        return view('pages.admin/dashboard',['users' => $users]);
    }
    public function update(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'username' => 'required|string|max:50|unique:User,username,' . $request->user_id,
            'email' => 'required|string|email|max:75|unique:User,email,' . $request->user_id,
            'password' => 'nullable|string|min:8|confirmed|regex:/[A-Z]/|regex:/[0-9]/',
          
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];

        if (!empty($request->password)) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect('/admin/dashboard/users')->with('success', 'Profile updated successfully!');
    }
    public function changeProduct(Request $request): View  
    {
    $validated = $request->validate([
        'quantity' => 'required|integer|min:0', 
        'discount' => 'nullable|numeric|min:0|max:100',  
    ]);

    $product = Product::findOrFail($request->product_id);
    $product->quantity = $validated['quantity'];
    $product->discount_percent = $validated['discount'];
    $product->save();

    return view('pages.admin/viewProduct', compact('product'));

    }
    public function createUserShow()
    {
        return view('pages.admin/createUser');  
    }

    public function createProductShow()
    {
        return view('pages.admin/createProduct'); 
    }
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255', 
            'username' => 'required|string|max:255|unique:User,username', 
            'email' => 'required|email|unique:User,email', 
            'password' => 'required|string|min:8|confirmed |regex:/[A-Z]/ |regex:/[0-9]/',
        ], [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name must not exceed 255 characters.',
            
            'username.required' => 'The username field is required.',
            'username.unique' => 'This username is already taken.',
            
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'This email is already taken.',
            
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters long.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.regex' => 'The password must include at least one uppercase letter and one number.',
        ]);
    
    
    $user = new User();
    $user->name = $validated['name'];
    $user->username= $validated['username'];
    $user->password = Hash::make($validated['password']);
    $user->email = $validated['email'];
   
    $user->save();

    return redirect('/admin/dashboard/users')->with('success', 'User created successfully!');
    }
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:category,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'The product name is required.',
            'price.numeric' => 'The price must be a valid number.',
            'discount.min' => 'The discount must be at least 0.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
        ]);
    
    
    $product = new Product();
    $product->name = $validated['name'];
    $product->category_id = $validated['category_id'];
    $product->description = $validated['description'];
    $product->price = $validated['price'];
    $product->quantity = $validated['quantity'];
    $product->discount_percent = $validated['discount'] ?? 0; 
    
    if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('dbimages', 'public');
    }
    
    $product->save();

    return redirect('/admin/dashboard/products')->with('success', 'Product created successfully!');
    }


   
}
