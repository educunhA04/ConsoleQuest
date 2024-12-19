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
use App\Events\NotificationPusher;
use Pusher\Pusher;

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
        
        $rules = [
            'NIF' => [
                'nullable',
                'digits:9',
            ],
            'credit_card_number' => [
                'required',
                'digits:16',
            ],
            'credit_card_exp_date' => [
                'required',
                'regex:/^(0[1-9]|1[0-2])\/[0-9]{4}$/',
                function ($attribute, $value, $fail) {
                    [$month, $year] = explode('/', $value);
                    $currentYear = now()->year;
                    $currentMonth = now()->month;
        
                    if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
                        $fail('Parece que o seu cartão de crédito expirou.');
                    }
                },
            ],
            'credit_card_cvv' => [
                'required',
                'digits:3',
            ],
            'address' => [
                'required',
                'string',
                'max:255',
            ],
            'postal_code' => [
                'required',
                'string',
                'max:20',
            ],
            'location' => [
                'required',
                'string',
                'max:255',
            ],
            'country' => [
                'required',
                'string',
                'max:100',
            ],
        ];
        
        $messages = [
            'NIF.digits' => 'O NIF deve conter 9 dígitos numéricos.',
            'credit_card_number.required' => 'O campo do número do cartão é obrigatório.',
            'credit_card_number.digits' => 'O número do cartão deve conter 16 dígitos numéricos.',
            'credit_card_exp_date.required' => 'O campo de data de validade é obrigatório.',
            'credit_card_exp_date.regex' => 'A data de validade deve estar no formato MM/YYYY.',
            'credit_card_cvv.required' => 'O campo CVV é obrigatório.',
            'credit_card_cvv.digits' => 'O CVV deve conter 3 dígitos numéricos.',
            'address.required' => 'O endereço é obrigatório.',
            'address.max' => 'O endereço não pode exceder 255 caracteres.',
            'postal_code.required' => 'O código postal é obrigatório.',
            'postal_code.max' => 'O código postal não pode exceder 20 caracteres.',
            'location.required' => 'A localidade é obrigatória.',
            'location.max' => 'A localidade não pode exceder 255 caracteres.',
            'country.required' => 'O país é obrigatório.',
            'country.max' => 'O país não pode exceder 100 caracteres.',
        ];
        
        $validated = $request->validate($rules, $messages);
    
        $creditCardExpDate = \Carbon\Carbon::createFromFormat('m/Y', $validated['credit_card_exp_date'])->startOfMonth()->format('Y-m-d');

        $order = Order::create([
            'user_id' => $user->id,
            'tracking_number' => uniqid('ORD_'),
            'status' => Order::STATUS_PROCESSING,
            'estimated_delivery_date' => now()->addDays(7),
            'buy_date' => now(),
            'postal_code'=> $request->input('postal_code'),
            'address'=> $request->input('address'),
            'location'=> $request->input('location'),
            'country'=> $request->input('country'),
        ]);
    
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'order_id' => $order->id,
            'code' => uniqid('TXN_'),
            'price' => $totalPrice,
            'nif' => $validated['NIF'],
            'credit_card_number' => $validated['credit_card_number'],
            'credit_card_exp_date' => $creditCardExpDate, 
            'credit_card_cvv' => $validated['credit_card_cvv'],
            'shipping_address' => $request->input('shipping_address'),
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

        event(new NotificationPusher($notification->id, $user->id));
    
        // Redirecione para uma página de confirmação
        return redirect()->route('home')->with('success', 'Compra finalizada com sucesso!');
    }

}
