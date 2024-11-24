<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use App\Models\Admin;
use App\Models\User;


class AdminController extends Controller
{
  
    public function show(): View
    {

        $users = User::orderBy('id')->get();
        return view('pages.admin/dashboard',['users' => $users]);
    }

    public function viewUser($name)
    {
        $user = User::findOrFail();  

        return view('pages.admin/viewUser', compact('user'));
    }

   
}
