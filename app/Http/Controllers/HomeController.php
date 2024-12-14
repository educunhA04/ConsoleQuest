<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Product;

class HomeController extends Controller
{
    
    public function show(): View
    {
        $products = Product::orderBy('id')->paginate(10);

        return view('Home', ['products' => $products]);
    }
    public function aboutus(): View
    {
        return view('pages.aboutus');
    }

    public function help(): View
    {
        return view('pages.help');
    }
    
    public function showControllers(): View
    {
        $products = Product::where('category_id', 3)->orderBy('id')->paginate(10);

        return view('Home', ['products' => $products]);
    }

    public function showGames(): View
    {
        $products = Product::where('category_id', 2)->orderBy('id')->paginate(10);

        return view('Home', ['products' => $products]);
    }

    public function showConsoles(): View
    {
        $products = Product::where('category_id', 1)->orderBy('id')->paginate(10);

        return view('Home', ['products' => $products]);
    }

    public function index(Request $request)
{
    // Get search query
    $query = $request->input('query', ''); // Search query from the search bar
    $sanitizedQuery = strtolower(trim($query));
    $queryNoSpaces = str_replace(' ', '', $sanitizedQuery);

    // Start building the query
    $products = Product::query();

    // Apply search filter
    if ($sanitizedQuery) {
        $products->where(function ($subQuery) use ($queryNoSpaces) {
            $subQuery->whereRaw('LOWER(REPLACE(name, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"])
                ->orWhereRaw('LOWER(REPLACE(description, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"])
                ->orWhereHas('category', function ($q) use ($queryNoSpaces) {
                    $q->whereRaw('LOWER(REPLACE(type::TEXT, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"]);
                });
        });
    }

    // Apply price range filters
    if ($request->filled('min_price')) {
        $products->where('price', '>=', $request->input('min_price'));
    }

    if ($request->filled('max_price')) {
        $products->where('price', '<=', $request->input('max_price'));
    }

    // Apply discount filter
    if ($request->boolean('discount_only')) {
        $products->where('discount_percent', '>', 0);
    }

    // Get the filtered products
    $products = $products->orderBy('id')->paginate(10);

    // Return the view
    return view('Home', compact('products', 'query'));
}


}