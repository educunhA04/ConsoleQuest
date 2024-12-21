<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Auth\Access\Response;

class WishlistPolicy
{
    public function view(User $user)
    {
        return true; 
    }

    public function create(User $user)
    {
        return true; 
    }

    public function delete(User $user, Wishlist $wishlist)
    {
        return $user->id === $wishlist->user_id;
    }
}
