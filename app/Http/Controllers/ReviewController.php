<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\OrderProduct;
use App\Models\Report;


class ReviewController extends Controller
{
    public function index($productId)
    {
        $reviews = Review::where('product_id', $productId)
            ->with('user') // Assuming relationship with User
            ->get();
        return view('reviews.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:product,id',
            'rating' => 'required|integer|min:0|max:5',
            'description' => 'required|string|max:500',
        ]);

        $existingReview = Review::where('user_id', auth()->id())
                ->where('product_id', $request->product_id)
                ->first();

        if ($existingReview) {
            return back()->with('error', 'Você já fez uma avaliação para este produto. Edite ou exclua a existente.');
        }

        // Check if the user has purchased the product
        $hasPurchased = OrderProduct::where('product_id', $request->product_id)
            ->whereHas('order', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'Só pode avaliar produtos que comprou.');
        }

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Avaliação criada com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $review = Review::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Valida os dados de entrada
        $request->validate([
            'rating' => 'required|integer|min:0|max:5',
            'description' => 'required|string|max:500',
        ]);

        // Atualiza a review
        $review->update([
            'rating' => $request->rating,
            'description' => $request->description,
        ]);

        return redirect()->route('product.show', $review->product_id)
               ->with('success', 'Avaliação atualizada com sucesso!');
    }

    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);
    
            // Check if the user is the owner or an admin
            if ($review->user_id !== auth()->id() && !auth()->user()->is_admin) {
                return back()->with('error', 'You do not have permission to delete this review.');
            }
    
            // Delete the review
            $review->delete();
    
            // Optionally, delete associated reports
            Report::where('review_id', $id)->delete();
    
            return back()->with('success', 'Review deleted successfully!');
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Review not found.');
        }
    }
    

    public function adminDestroy($id)
    {
        try {
            $review = Review::findOrFail($id);

            // Delete the review
            $review->delete();

            // Optionally, delete associated reports
            Report::where('review_id', $id)->delete();

            return redirect()->route('admin.dashboard.reports')->with('success', 'Review deleted successfully!');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.dashboard.reports')->with('error', 'Review not found.');
        }
    }


}
