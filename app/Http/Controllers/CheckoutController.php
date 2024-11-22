<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\ShoppingCart;

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

         // Cria a transação com o user_id
         $order = Order::create([
            'tracking_number' => uniqid('ORD_'),
            'status' => Order::STATUS_PROCESSING,
            'estimated_delivery_date' => now()->addDays(7),
            'buy_date' => now(),
            //'transaction_id' => null, // Temporarily set as null
        ]);
    
        // Create the transaction with the order_id
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'order_id' => $order->id, // Link the transaction to the order
            'code' => uniqid('TXN_'),
            'price' => $totalPrice,
            'nif' => $request->input('NIF'), // Optional fields
            'credit_card_number' => $request->input('credit_card_number'),
            'credit_card_exp_date' => $request->input('credit_card_exp_date'),
            'credit_card_cvv' => $request->input('credit_card_cvv'),
        ]);
    
        // Update the order with the transaction_id
        $order->update([
            'transaction_id' => $transaction->id,
        ]);
    
        
        // Adicione os produtos à tabela OrderProduct e diminua a quantidade no estoque
        foreach ($cartItems as $cartItem) {
            OrderProduct::create([
                'order_id' => $order->id,
                'product_id' => $cartItem->product->id,
                'quantity' => $cartItem->quantity,
            ]);
    
            // Atualize a quantidade do produto no estoque
            $cartItem->product->decrement('quantity', $cartItem->quantity);
        }
    
        // Limpe o carrinho do usuário
        ShoppingCart::where('user_id', $user->id)->delete();
    
        // Redirecione para uma página de confirmação
        return redirect()->route('home')->with('success', 'Compra finalizada com sucesso!');
    }

}
