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
    $query = $request->input('query', '');
    $sanitizedQuery = strtolower(trim($query));
    $queryNoSpaces = str_replace(' ', '', $sanitizedQuery);

    $products = Product::query();

    // Apply search filter
    if ($sanitizedQuery) {
        $products->whereRaw('LOWER(REPLACE(name, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"])
            ->orWhereRaw('LOWER(REPLACE(description, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"])
            ->orWhereHas('category', function ($q) use ($queryNoSpaces) {
                $q->whereRaw('LOWER(REPLACE(type::TEXT, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"]);
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

    $products = $products->get();

    if ($request->ajax()) {
        // Return partial view for AJAX
        $html = view('partials.products', compact('products'))->render();
        return response()->json(['html' => $html]);
    }

    return view('Home', compact('products', 'query'));
}
}