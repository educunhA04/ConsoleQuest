<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Import the Product model
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderProduct;
use App\Models\Review;


class ProductPageController extends Controller
{
    public function show(int $id): View
    {
        $product = Product::with(['reviews.user'])->findOrFail($id);

        $hasPurchased = false;

        if (Auth::check()) { // Verifica se o usuário está autenticado
            $hasPurchased = OrderProduct::where('product_id', $id)
                            ->whereHas('order', function ($query) {
                                $query->where('user_id', auth()->id());
                            })
                            ->exists();
        }

        // Verificar se o usuário já fez uma review para o produto
        $existingReview = null;

        if (Auth::check()) {
            $existingReview = Review::where('user_id', auth()->id())
                                    ->where('product_id', $id)
                                    ->first();
        }

        return view('pages/product', compact('product', 'hasPurchased', 'existingReview'));
    }
}

