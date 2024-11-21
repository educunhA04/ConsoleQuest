<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class ShoppingCartController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return View
     */

    public function show()
    {
        return view('pages/shoppingcart'); 
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

}
