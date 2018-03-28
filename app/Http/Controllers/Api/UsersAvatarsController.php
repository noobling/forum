<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersAvatarsController extends Controller
{
    /**
     * Only allow authenticated users
     *
     * UsersAvatarsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image'
        ]);

        auth()->user()->update([
            'avatar_path' => request()->file('avatar')->store('avatars', 'public')
        ]);
    }
}
