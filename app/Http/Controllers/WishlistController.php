<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;



class WishlistController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return View
     */

    public function show()
    {
        $userId = auth()->id();
     
         $wishlistItems = Wishlist::with('product') 
             ->where('user_id', $userId)
             ->get();
        return view('pages/wishlist',['wishlistItems' => $wishlistItems]); 
    }
    public function add(Request $request)
    {
    $validated = $request->validate([
        'product_id' => 'required|integer|exists:product,id',
    ]);

    $userId = auth()->id();
    $wishlistItem = Wishlist::where('user_id', $userId)
                            ->where('product_id', $validated['product_id'])
                            ->first();

    if ($wishlistItem) {
        return redirect()->back()->with('info', 'Item já está na sua wishlist.');
    } else {
        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $validated['product_id'],
    ]);
    }
    
    
    

    return redirect()->back()->with('success', 'Item added to cart successfully.');
    }   

}
