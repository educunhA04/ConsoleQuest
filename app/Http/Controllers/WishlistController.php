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
        if (!auth()->check()) {
            return redirect()->route('login'); 
        }
        $userId = auth()->id();
     
        $wishlistItems = Wishlist::with('product') 
             ->where('user_id', $userId)
             ->get();
        return view('pages/wishlist',['wishlistItems' => $wishlistItems]); 
    }

    public function remove(Request $request, $id)
    {
        $wishlistItem = Wishlist::findOrFail($id);
        $wishlistItem->delete();

        return redirect()->back()->with('success', 'Item removed from wishlist.');
    }
    
    
    public function add(Request $request)
    {
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'error' => 'You must be logged in to add items to your wishlist.'
            ], 401);
        }
    
        try {
            // Validate request
            $validated = $request->validate([
                'product_id' => 'required|integer|exists:product,id',
            ]);
    
            $userId = auth()->id();
    
            // Check if the product is already in the wishlist
            $wishlistItem = Wishlist::where('user_id', $userId)
                                    ->where('product_id', $validated['product_id'])
                                    ->first();
    
            if ($wishlistItem) {
                return response()->json([
                    'info' => 'Item already exists in your wishlist.'
                ], 200);
            }
    
            // Add item to the wishlist if it's not already there
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $validated['product_id'],
            ]);
    
            return response()->json([
                'message' => 'Item added to wishlist successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    

}
