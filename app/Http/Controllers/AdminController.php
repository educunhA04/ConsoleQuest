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

    public function viewUser(Request $request): View
    {
        $user = User::findOrFail($request->input('user_id')); 
        return view('pages.admin/viewUser', ['user' => $user]);
    }
    public function changeUser(Request $request): View
    {
        $user = User::findOrFail($request->input('user_id')); 
        return view('pages.admin/changeUser', ['user' => $user]);
    }
    public function showFiltredUsers(Request $request): View
    {
        $query = $request->input('query', '');
        $sanitizedQuery = strtolower(trim($query)); // Converte para minúsculas e remove espaços extras
        $queryNoSpaces = str_replace(' ', '', $sanitizedQuery); // Remove todos os espaços

        $users = User::query();
        if ($sanitizedQuery) {
            $users = $users->whereRaw('LOWER(REPLACE(name, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"])
                ->orWhereRaw('LOWER(REPLACE(username, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"])
                ->orWhereRaw('LOWER(REPLACE(email, \' \', \'\')) LIKE ?', ["%{$queryNoSpaces}%"]);
                
        }
        $users = $users->get();


        return view('pages.admin/dashboard',['users' => $users]);
    }
    public function update(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'username' => 'required|string|max:50|unique:User,username,' . $request->user_id,
            'email' => 'required|string|email|max:75|unique:User,email,' . $request->user_id,
            'password' => 'nullable|string|min:8|confirmed|regex:/[A-Z]/|regex:/[0-9]/',
          
        ]);

        $user = User::findOrFail($request->input('user_id'));
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];

        if (!empty($request->password)) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect('/admin/dashboard')->with('success', 'Profile updated successfully!');
    }

   
}
