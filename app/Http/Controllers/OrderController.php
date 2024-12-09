<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderProduct;

class OrderController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $orders = Order::where('user_id', auth()->id())->with('products.product')->get();

        
    }

    public function show($orderId)
    {
        $userId = auth()->id();

        // Encontre o pedido do usuário
        $order = Order::where('user_id', $userId)->findOrFail($orderId);

        // Obtenha os produtos associados ao pedido
        $orderProducts = OrderProduct::where('order_id', $orderId)->get();

        // Retorne os dados do pedido como JSON
        return response()->json([
            'tracking_number' => $order->tracking_number,
            'buy_date' => $order->buy_date->format('Y-m-d'),
            'status' => ucfirst($order->status),
            'total' => $orderProducts->sum(function($item) { return $item->quantity * $item->price; }),
            'products' => $orderProducts->map(function($item) {
                return [
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ];
            })
        ]);
    }


    public function cancelOrder(Request $request, $orderId)
{
    // Retrieve the order by ID
    $order = Order::find($orderId);

    // Check if the order exists
    if (!$order) {
        return redirect()->back()->with('error', 'Order not found');
    }

    // Check if the order belongs to the authenticated user
    if ($order->user_id !== auth()->id()) {
        return redirect()->back()->with('error', 'Unauthorized action');
    }

    // Check if the order status allows cancellation
    if ($order->status !== 'processing') {
        return redirect()->back()->with('error', 'Order cannot be cancelled at this stage');
    }

    // Fetch products and quantities through the OrderProduct relationship
    $orderProducts = $order->orderProducts;

    // Loop through the products and restore stock
    foreach ($orderProducts as $orderProduct) {
        $product = $orderProduct->product;

        if ($product) {
            // Update the product's stock
            $product->quantity += $orderProduct->quantity;
            $product->save();
        }
    }

    // Update the order status to 'cancelled'
    $order->status = 'cancelled';
    $order->save();

    // Return success message
    return redirect()->back()->with('success', 'Order cancelled successfully!');
}
    


}