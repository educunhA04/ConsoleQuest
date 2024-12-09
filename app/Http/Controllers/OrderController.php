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
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Check if the order belongs to the authenticated user
        if ($order->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized action'], 403);
        }

        // Check if the order status allows cancellation
        if ($order->status !== 'processing') {
            return response()->json(['error' => 'Order cannot be cancelled at this stage'], 400);
        }

        // Update the order status to 'cancelled'
        $order->status = 'cancelled';
        $order->save();

        // Restore product stock if necessary
        foreach ($order->products as $product) {
            $product->quantity += $product->pivot->quantity;
            $product->save();
        }

        // Return success message
        return response()->json(['success' => true, 'message' => 'Order cancelada com sucesso!']);
    }


}