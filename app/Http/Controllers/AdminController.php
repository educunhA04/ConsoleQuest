<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Import models
use App\Models\Admin;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\Product;
use App\Models\ShoppingCart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Report;
use App\Models\Review;
use App\Models\PasswordResetToken;



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

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect('/admin/dashboard/users')->with('success', 'Profile updated successfully!');
    }

    public function changeProduct(Request $request)  
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'description' => 'required|string',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
        ], [
            'name.required' => 'The product name is required.',
            'type.required' => 'The product type is required.',
            'price.numeric' => 'The price must be a valid number.',
            'discount.min' => 'The discount must be at least 0.',
        ]);
        $product = Product::findOrFail($request->product_id);
        if ($product->quantity == 0 && $validated['quantity'] > 0) {
            $wishlistUsers = Wishlist::where('product_id', $product->id)->pluck('user_id');
            
            if ($wishlistUsers->isNotEmpty()) { 
                $notification = new Notification();
                $notification->description = "Product in wishlist '{$validated['name']}' is now available.";
                $notification->viewed = false;
                $notification->date = now();
                $notification->save(); 
    
                foreach ($wishlistUsers as $userId) {
                    NotificationUser::create([
                        'user_id' => $userId,
                        'notification_id' => $notification->id,
                    ]);
                }
            }
        }
        if ($product->price != $validated['price']) {
            $cartUsers = ShoppingCart::where('product_id', $product->id)->pluck('user_id');
    
            if ($cartUsers->isNotEmpty()) {
                $notificationCart = new Notification();
                $notificationCart->description = "Product '{$validated['name']}' price changed, now {$validated['price']}€.";
                $notificationCart->viewed = false;
                $notificationCart->date = now();
                $notificationCart->save();
    
                foreach ($cartUsers as $userId) {
                    NotificationUser::create([
                        'user_id' => $userId,
                        'notification_id' => $notificationCart->id,
                    ]);
                }
            }
        }
        $product->name = $validated['name'];
        $product->category_id = $validated['category_id'];
        $product->description = $validated['description'];
        $product->price = $validated['price'];
        $product->type = $validated['type'];
        $product->quantity = $validated['quantity'];
        $product->discount_percent = $validated['discount'] ?? 0; 

        $product->save();

        return redirect('/admin/dashboard/products')->with('success', 'Product created successfully!');

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
            'category_id' => 'required|integer',
            'description' => 'required|string',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
        ], [
            'name.required' => 'The product name is required.',
            'type.required' => 'The product type is required.',
            'price.numeric' => 'The price must be a valid number.',
            'discount.min' => 'The discount must be at least 0.',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
    
    $product = new Product();
    $product->name = $validated['name'];
    $product->category_id = $validated['category_id'];
    $product->description = $validated['description'];
    $product->price = $validated['price'];
    $product->quantity = $validated['quantity'];
    $product->type = $validated['type'];
    $product->discount_percent = $validated['discount'] ?? 0; 
    
    if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('dbimages', 'public');
    }
    
    $product->save();

    return redirect('/admin/dashboard/products')->with('success', 'Product created successfully!');
    }


    public function viewUserOrders($userId): View
    {
        $user = User::with(['orders.orderProducts.product'])->findOrFail($userId);
        return view('pages.admin.adminUserOrders', compact('user'));
    }

    public function viewOrderDetails($orderId): \Illuminate\Http\JsonResponse
    {
        $order = Order::with(['orderProducts.product', 'user'])->findOrFail($orderId);

        return response()->json([
            'tracking_number' => $order->tracking_number,
            'date' => $order->buy_date->format('Y-m-d'), // Ensure `buy_date` is cast to a date
            'status' => ucfirst($order->status),
            'total' => $order->orderProducts->sum(fn($op) => $op->quantity * $op->product->price),
            'products' => $order->orderProducts->map(fn($op) => [
                'name' => $op->product->name,
                'quantity' => $op->quantity,
                'price' => $op->product->price,
                'image' => $op->product->image ? asset("storage/{$op->product->image}") : null,
            ]),
        ]);
    }

    public function showReports()
    {
        // Fetch all user reports with pagination
        $reports = Report::with('user', 'review')
            ->orderBy('id', 'desc')
            ->paginate(10); // Adjust the pagination as needed

        // Return the reports view
        return view('pages.admin.dashboard', compact('reports'));
    }

    public function handleReport($id)
    {
        try {
            $report = Report::findOrFail($id);
            $report->delete();

            return redirect()->route('admin.dashboard.reports')->with('success', 'Report marked as resolved.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.dashboard.reports')->with('error', 'Report not found.');
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard.reports')->with('error', 'An unexpected error occurred.');
        }
    }


    public function deleteReview($id)
    {
        $review = Review::find($id);

        if (!$review) {
            return redirect()->back()->with('error', 'Review not found.');
        }

        // Delete associated reports
        Report::where('review_id', $id)->delete();

        // Delete the review
        $review->delete();

        return redirect()->route('admin.dashboard.reports')->with('success', 'Review and associated reports deleted successfully.');
    }

    public function deleteReport($id)
    {
        // Find the report by ID
        $report = Report::find($id);

        // Check if the report exists
        if (!$report) {
            return redirect()->back()->with('error', 'Report not found.');
        }

        // Delete the report
        $report->delete();

        return redirect()->route('admin.dashboard.reports')->with('success', 'Report deleted successfully.');
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:processing,shipped,delivered,cancelled',
            'user_id' => 'required|integer|exists:User,id',
        ]);
    
        $order = Order::find($id);
    
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found.');
        }
        $user = User::findOrFail($request->user_id);
        $old_status = $order->status;
        $order->status = $request->input('status');
        if ($old_status == $order->status) {
            return view('pages.admin/viewUser', ['user' => $user]);
        }
        $order->save();
        $notification = new Notification();
        $notification->description = "Order status changed from  " . $old_status . " to " . $order->status ;
        $notification->viewed = FALSE;
        $notification->date = Now();
        $notification->save(); 
        NotificationUser::create([
            'user_id' => $request->user_id,
            'notification_id' => $notification->id,
        ]);
        return view('pages.admin/viewUser', ['user' => $user]);
    }
    public function deleteUser(Request $request)
    {
        // Validate the user ID
        $request->validate([
            'user_id' => 'required|exists:User,id',
        ]);

        // Find and delete the user
        $user = User::find($request->user_id);
        if ($user) {

            PasswordResetToken::where('email', $user->email)->delete();

            $user->name = 'Anonymous' . $user->id;
            $user->username = 'anonymous'. $user->id;
            $user->email = 'anonymous' . $user->id . '@anonymous.com';
            $user->image = null; // Remove the profile picture
            $user->blocked = true; // Block the user

            $user->save();
            return redirect('/admin/dashboard/users')->with('success', 'User deleted successfully.');
        }

        return redirect()->back()->with('error', 'User not found.');
    }

    public function blockUser(Request $request, $id)
    {
        // Find the user by ID
        $user = User::find($id);
        if ($user) {
            $user->blocked = true; // Block the user
            $user->save();
            
            return redirect('/admin/dashboard/users')->with('success', 'User blocked successfully.');
        }

        return redirect()->back()->with('error', 'User not found.');
    }
    public function unblockUser(Request $request, $id)
    {
        // Find the user by ID
        $user = User::find($id);
        if ($user) {
            $user->blocked = false; // Block the user
            $user->save();
            
            return redirect('/admin/dashboard/users')->with('success', 'User blocked successfully.');
        }

        return redirect()->back()->with('error', 'User not found.');
    }
}
