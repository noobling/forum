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
     * Create the favourite
     *
     * @param Reply $reply
     * @return mixed
     */
    public function store(Reply $reply)
    {
        $reply->favourite();

        return back();
    }

    /**
     * Delete the favourite
     *
     * @param Reply $reply
     */
    public function destroy(Reply $reply)
    {
        $reply->unfavourite();
    }
}
