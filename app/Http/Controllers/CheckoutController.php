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

        // Crie a transação
        $transaction = Transaction::create([
            'code' => uniqid('TXN_'),
            'price' => $totalPrice,
            'NIF' => $request->input('NIF'), // Certifique-se de que o campo NIF é passado no formulário
            'credit_card_number' => $request->input('credit_card_number'),
            'credit_card_exp_date' => $request->input('credit_card_exp_date'),
            'credit_card_cvv' => $request->input('credit_card_cvv'),
        ]);

        // Crie a ordem
        $order = Order::create([
            'tracking_number' => uniqid('ORD_'),
            'status' => Order::STATUS_PROCESSING,
            'estimated_delivery_date' => now()->addDays(7), // Exemplo: entrega estimada em 7 dias
            'buy_date' => now(),
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
        return redirect()->route('home')->with('success', 'Compra finalizada com sucesso! Acompanhe seu pedido na seção de pedidos.');
    }
}
