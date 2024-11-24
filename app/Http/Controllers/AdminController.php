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

public function viewUser($id)
{
    // Find the user by ID
    $user = User::findOrFail($id);  // Will throw 404 if user not found

    // Pass the user data to the view
    return view('admin.viewUser', compact('user'));
}

   
}
