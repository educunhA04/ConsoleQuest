<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Show a single product by ID.
     */
    public function show(int $id): View
    {
        // Fetch the product by ID or fail with a 404 error if not found
        $product = Product::findOrFail($id);

        // Check if the current user is authorized to view the product
        $this->authorize('view', $product);

        return view('product.show', compact('product')); // Return the view
        /*
        // Return the product details to the 'products.show' view
        return view('products.show', [
            'product' => $product
        ]);
        */
    }

 

    /**
     * Display a listing of all products.
     */
    public function index(): View
    {
        // Fetch all products
        $products = Product::orderBy('id')->get();

        // Return the products list to the 'products.index' view
        return view('products.index', [
            'products' => $products
        ]);
    }
}