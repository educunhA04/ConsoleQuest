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

class AdminController extends Controller
{
    public function show(): View
    {
        $users = User::orderBy('id')->get();
        return view('pages.admin.dashboard', ['users' => $users]);
    }

    public function showProducts(): View
    {
        $products = Product::orderBy('id')->get();
        return view('pages.admin.dashboard', ['products' => $products]);
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
        return view('pages.admin.changeUser', ['user' => $user]);
    }

    public function showFiltredUsers(Request $request): View
    {
        $query = strtolower(trim($request->input('query', '')));
        $queryNoSpaces = str_replace(' ', '', $query);

        $users = User::query()
            ->when($query, function ($queryBuilder) use ($queryNoSpaces) {
                $queryBuilder->whereRaw('LOWER(REPLACE(name, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"])
                    ->orWhereRaw('LOWER(REPLACE(username, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"])
                    ->orWhereRaw('LOWER(REPLACE(email, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"]);
            })
            ->get();

        return view('pages.admin.dashboard', ['users' => $users]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'username' => 'required|string|max:50|unique:users,username,' . $request->user_id,
            'email' => 'required|string|email|max:75|unique:users,email,' . $request->user_id,
            'password' => 'nullable|string|min:8|confirmed|regex:/[A-Z]/|regex:/[0-9]/',
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $user->password,
        ]);

        return redirect()->route('admin.dashboard.users')->with('success', 'Profile updated successfully!');
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

}
