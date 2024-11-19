<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class ProductPageController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return View
     */

    public function show()
    {
        return view('pages/product'); 
    }

}