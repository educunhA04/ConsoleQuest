<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Product;
use App\Models\Type;

class HomeController extends Controller
{
    
    public function show(): View
    {
        $products = Product::orderBy('id')->paginate(10);
        $types = Type::all();

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
    $types = Type::all();
    
    // Get search query
    $query = $request->input('query', ''); // Search query from the search bar
    $sanitizedQuery = strtolower(trim($query));
    $queryNoSpaces = str_replace(' ', '', $sanitizedQuery);


    // Start building the query
    $products = Product::query();

    // Apply search filter
    if ($sanitizedQuery && $sanitizedQuery !== 'home') {
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
        $products->where(function ($q) use ($request) {
            $q->where('price', '>=', $request->input('min_price'))
                ->orWhereRaw('(price - (price * discount_percent / 100)) >= ?', [$request->input('min_price')]);
        });
    }
    
    if ($request->filled('max_price')) {
        $products->where(function ($q) use ($request) {
            $q->where('price', '<=', $request->input('max_price'))
                ->orWhereRaw('(price - (price * discount_percent / 100)) <= ?', [$request->input('max_price')]);
        });
    }

    // Apply discount filter
    if ($request->boolean('discount_only')) {
        $products->where('discount_percent', '>', 0);
    }

    // Apply type filter
    if ($request->filled('type_id')) {
        $products->where('type_id', $request->input('type_id'));
    }

    // Paginate the results and append filters
    $products = $products->orderBy('id')->paginate(10)
        ->appends([
            'query' => $request->input('query'),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'discount_only' => $request->input('discount_only'),
            'type_id' => $request->input('type_id'),
        ]);

    // Pass $types to the view
    return view('Home', compact('products', 'query', 'types'));
}



}