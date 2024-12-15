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
        if (auth()->check()) {
            $userId = auth()->id();
        
            $cartItems = ShoppingCart::with('product')
                ->where('user_id', $userId)
                ->get();
        
            $totalPrice = $cartItems->sum(function ($cartItem) {
                return $cartItem->quantity * $cartItem->product->price;
            });
        
            return view('pages.shoppingcart', compact('cartItems', 'totalPrice'));
        }
        else {
            $sessionCart = session('cart', []);
            $cartItems = collect($sessionCart)->map(function ($item) {
                $product = \App\Models\Product::find($item['product_id']);
                return [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product ? $product->price * $item['quantity'] : 0
                ];
            });
    
            $totalPrice = $cartItems->sum('price');
    
            return view('pages.shoppingcart', compact('cartItems', 'totalPrice'));
        }
    }
    


    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:product,id',
            'quantity' => 'required|integer|min:1',
        ]);

        if (auth()->check()) {
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
        } else {
            // Armazenar na sessão se o usuário não estiver autenticado
            $cart = session()->get('cart', []);

            if (isset($cart[$validated['product_id']])) {
                $cart[$validated['product_id']]['quantity'] += $validated['quantity'];
            } else {
                $cart[$validated['product_id']] = [
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity']
                ];
            }

            session()->put('cart', $cart);

            return response()->json([
                'message' => 'Item added to cart successfully.',
                'cart' => $cart
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

    public function remove(Request $request)
    {
        if (Auth::check()) {
            // For logged-in users, remove the item from the database (your shopping cart table)
            $cartItem = ShoppingCart::find($request->cart_item_id);
            if ($cartItem) {
                $cartItem->delete();
            }
        } else {
            // For unauthenticated users, remove the item from the session
            $cart = session('cart', []);
            $cart = collect($cart)->filter(function ($item) use ($request) {
                return $item['product_id'] != $request->cart_item_id;
            })->values()->all();
            session(['cart' => $cart]);
        }
    
        return redirect()->back()->with('success', 'Item removido com sucesso.');
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
