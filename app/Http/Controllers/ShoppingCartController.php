<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\ShoppingCart;

class ShoppingCartController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return View
     */

     public function show()
     {
         // Get the authenticated user's ID
         $userId = auth()->id();
     
         // Fetch the shopping cart items for the user, including the related product details
         $cartItems = ShoppingCart::with('product') // Assuming a 'product' relationship exists
             ->where('user_id', $userId)
             ->get();

             $totalPrice = $cartItems->sum(function ($cartItem) {
                return $cartItem->quantity * $cartItem->product->price;
            });
     
         // Pass the cart items to the view
         return view('pages.shoppingcart', compact('cartItems', 'totalPrice'));
     }
     
    public function add(Request $request)
    {
    $validated = $request->validate([
        'product_id' => 'required|integer|exists:product,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $userId = auth()->id();

    $cartItem = ShoppingCart::where('user_id', $userId)
                            ->where('product_id', $validated['product_id'])
                            ->first();

    if ($cartItem) {
        $cartItem->increment('quantity', $validated['quantity']);
    } else {
        ShoppingCart::create([
            'user_id' => $userId,
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
        ]);
    }

    return redirect()->back()->with('success', 'Item added to cart successfully.');
    }   

    public function update(Request $request)
    {
        $validated = $request->validate([
            'cart_item_id' => 'required|exists:shopping_cart,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = ShoppingCart::findOrFail($validated['cart_item_id']);
        $cartItem->update(['quantity' => $validated['quantity']]);

        return redirect()->back()->with('success', 'Quantidade atualizada com sucesso.');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'cart_item_id' => 'required|exists:shopping_cart,id',
        ]);

        ShoppingCart::findOrFail($validated['cart_item_id'])->delete();

        return redirect()->back()->with('success', 'Produto removido do carrinho.');
    }

    public function clear()
    {
        $userId = auth()->id();
        ShoppingCart::where('user_id', $userId)->delete();

        return redirect()->back()->with('success', 'Carrinho limpo com sucesso.');
    }

    public function checkout()
    {
        $userId = auth()->id();

        $cartItems = ShoppingCart::where('user_id', $userId)->get();

        // Calcule o total do carrinho
        $totalPrice = $cartItems->sum(function ($cartItem) {
            return $cartItem->quantity * $cartItem->product->price;
        });

        return view('pages.checkout', compact('cartItems', 'totalPrice'));
    }
    public function finalize(Request $request)
    {
        $userId = auth()->id();

        // Obtenha os itens do carrinho do usuário autenticado
        $cartItems = ShoppingCart::where('user_id', $userId)->get();

        // Simulação do processo de compra (ex: salvar pedido no banco de dados)
        foreach ($cartItems as $cartItem) {
            // Aqui você pode criar uma ordem ou processar o pedido.
            // Exemplo:
            // Order::create([...]);
        }

        // Limpe o carrinho após a compra
        ShoppingCart::where('user_id', $userId)->delete();

        // Redirecione para uma página de confirmação
        return redirect()->route('home')->with('success', 'Compra finalizada com sucesso!');
    }


}
