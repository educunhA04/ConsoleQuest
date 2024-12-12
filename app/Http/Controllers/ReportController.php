<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Report;
use App\Models\Review;

class ReportController extends Controller
{
    public function report(Request $request, $id)
    {
        $review = Review::find($id);

        $validatedData = $request->validate([
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        

        
    if (!$review) {
        return response()->json(['message' => 'Review not found'], 404);
    }
    Report::create([
        'review_id' => $review->id,
        'user_id' => $review->user_id,
        'reason' => $request->reason,
        'description' => $request->description,
    ]);
 

    return redirect()->route('product.show', $review->product_id)
    ->with('success', 'Avaliação atualizada com sucesso!');    }
    
}