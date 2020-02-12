<?php

namespace App\Http\Controllers;

use App\User;
use App\Profile;
use Illuminate\Http\Request;

class FollowsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Grąžiną ManyToMany sąryšį tarp User ir Profile modelių,
     * User gali sekti daug Profile
    */
    public function store(User $user)
    {
      return auth()->user()->following()->toggle($user->profile);
    }
}
