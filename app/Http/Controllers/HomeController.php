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

    public function aboutus(): View
    {
        return view('pages.aboutus');
    }

    public function showControllers(): View
    {
        $products = Product::where('category_id', 3)->orderBy('id')->get();
        return view('Home', ['products' => $products]);
    }

    public function showGames(): View
    {
        $products = Product::where('category_id', 2)->orderBy('id')->get();
        return view('Home', ['products' => $products]);
    }

    public function showConsoles(): View
    {
        $products = Product::where('category_id', 1)->orderBy('id')->get();
        return view('Home', ['products' => $products]);
    }

    public function index(Request $request)
    {
        // Captura a query
        $query = $request->input('query', '');
        $sanitizedQuery = strtolower(trim($query)); // Converte para minúsculas e remove espaços extras
        $queryNoSpaces = str_replace(' ', '', $sanitizedQuery); // Remove todos os espaços

        $products = Product::query();

        if ($sanitizedQuery) {
            $products = $products->whereRaw('LOWER(REPLACE(name, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"])
                ->orWhereRaw('LOWER(REPLACE(description, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"])
                ->orWhereHas('category', function ($q) use ($queryNoSpaces) {
                    $q->whereRaw('LOWER(REPLACE(type::TEXT, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"]);
                });
        }

        // Pega os resultados
        $products = $products->get();

        // Retorna a view com os produtos
        return view('Home', compact('products', 'query'));
    }
}