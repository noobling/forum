<?php

namespace App\Http\Controllers;

use App\Favourite;
use App\Reply;
use Illuminate\Http\Request;

class FavouritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Reply $reply
     * @return mixed
     */
    public function store(Reply $reply)
    {
        return $reply->favourite();
    }
}
