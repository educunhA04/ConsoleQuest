<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\ShoppingCart;
use App\Models\Notification;
use App\Models\NotificationUser;

class CheckoutController extends Controller
{
    /**
     * Handle the checkout process.
     */
    public function checkout(Request $request)
    {
        $user = Auth::user();

        // Obtenha os itens do carrinho do usuário autenticado
        $cartItems = ShoppingCart::with('product')->where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('shoppingcart')->with('error', 'Seu carrinho está vazio!');
        }

        // Calcule o total da compra
        $totalPrice = $cartItems->sum(function ($cartItem) {
            return $cartItem->quantity * $cartItem->product->price;
        });

         // Retorne a view de checkout
        return view('pages.checkout', compact('cartItems', 'totalPrice'));

    }

    public function finalize(Request $request)
    {
        $user = Auth::user();
    
        // Obtenha os itens do carrinho do usuário autenticado
        $cartItems = ShoppingCart::with('product')->where('user_id', $user->id)->get();
    
        if ($cartItems->isEmpty()) {
            return redirect()->route('shoppingcart')->with('error', 'Seu carrinho está vazio!');
        }
    
        // Calcule o total da compra
        $totalPrice = $cartItems->sum(function ($cartItem) {
            return $cartItem->quantity * $cartItem->product->price;
        });
        $validated = $request->validate([
            'NIF' => 'string|max:15',
            'credit_card_number' => 'required|digits:16',
            'credit_card_exp_date' => 'required|date|after:today',
            'credit_card_cvv' => 'required|digits:3',
        ]);
    
        $order = Order::create([
            'user_id' => $user->id,
            'tracking_number' => uniqid('ORD_'),
            'status' => Order::STATUS_PROCESSING,
            'estimated_delivery_date' => now()->addDays(7),
            'buy_date' => now(),
        ]);
    
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'order_id' => $order->id, 
            'code' => uniqid('TXN_'),
            'price' => $totalPrice,
            'nif' => $validated['NIF'],
            'credit_card_number' => $validated['credit_card_number'], 
            'credit_card_exp_date' => $validated['credit_card_exp_date'], 
            'credit_card_cvv' => $validated['credit_card_cvv'], 
        ]);
     
        foreach ($cartItems as $cartItem) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product->id,
                'quantity' => $cartItem->quantity,
            ]);
            $cartItem->product->decrement('quantity', $cartItem->quantity);
        }
        $notification = Notification::create([
            'description' => "Compra efetuada com sucesso" ,
            'viewed' => FALSE,
            'date' => Now(),
        ]);
        $notification_user = NotificationUser::create([
            'user_id' => $user->id,
            'notification_id' => $notification->id,
        ]);
        ShoppingCart::where('user_id', $user->id)->delete();
    
        // Redirecione para uma página de confirmação
        return redirect()->route('home')->with('success', 'Compra finalizada com sucesso!');
    }

}
