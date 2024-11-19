<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Display the home page with a list of products (if needed).
     */
    public function show(): View
    {
        // Fetch all the products
        $products = Product::class::orderBy('id')->get();

        return view('Home', ['products' => $products]);
    }
}