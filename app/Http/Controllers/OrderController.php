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

        // Encontre os pedidos do usuário
        $orders = Order::where('user_id', $userId)->get();

        // Retorne a view com os dados dos pedidos
        return view('partials.orders', compact('orders'));
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
            'order' => $order,
            'orderProducts' => $orderProducts
        ]);
    }
}