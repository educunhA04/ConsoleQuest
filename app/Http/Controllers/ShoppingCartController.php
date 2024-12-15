<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\ShoppingCart;

class ShoppingCartController extends Controller
{
    /**
     * Exibir os itens do carrinho.
     *
     * @return View
     */
    public function show()
    {   
        if (!auth()->check()) {
            return redirect()->route('login'); 
        }
        $userId = auth()->id();

        $cartItems = ShoppingCart::with('product')
            ->where('user_id', $userId)
            ->get();

        $totalPrice = $cartItems->sum(function ($cartItem) {
            return $cartItem->quantity * $cartItem->product->price;
        });

        return view('pages.shoppingcart', compact('cartItems', 'totalPrice'));
    }


    public function add(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'error' => 'Você precisa estar logado para adicionar itens ao carrinho.'
            ], 401);
        }
    
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
            return response()->json([
                'message' => 'Item quantity updated in cart successfully.',
                'cart_item' => $cartItem
            ], 200);
        } else {
            ShoppingCart::create([
                'user_id' => $userId,
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
    
            return response()->json([
                'message' => 'Item added to cart successfully.'
            ], 201);
        }
        }
    


    /**
     * Atualizar a quantidade de um item no carrinho.
     */
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

    /**
     * Remover um item do carrinho.
     */
    public function remove(Request $request)
    {
        $validated = $request->validate([
            'cart_item_id' => 'required|exists:shopping_cart,id',
        ]);

        ShoppingCart::findOrFail($validated['cart_item_id'])->delete();

        return redirect()->back()->with('success', 'Produto removido do carrinho.');
    }

    /**
     * Limpar o carrinho de compras do usuário.
     */
    public function clear()
    {
        $userId = auth()->id();
        ShoppingCart::where('user_id', $userId)->delete();

        return redirect()->back()->with('success', 'Carrinho limpo com sucesso.');
    }

    /**
     * Redirecionar para a página de checkout.
     */
    public function checkout()
    {
        $userId = auth()->id();

        $cartItems = ShoppingCart::where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.show')->with('error', 'O seu carrinho está vazio!');
        }

        // Redireciona para a página de checkout
        return redirect()->route('checkout.show');
    }
}
