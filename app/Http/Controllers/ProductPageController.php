<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Import the Product model
use Illuminate\View\View;

class ProductPageController extends Controller
{
    public function show($id): View
    {
        // Fetch the product by ID or fail with a 404 error if not found
        $product = Product::findOrFail($id);

        // Return the 'product' view with the product data
        return view('pages.product', compact('product'));
    }
}

