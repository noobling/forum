<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Thread;

class SubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($channelId, Thread $thread)
    {
        return $thread->subscribe();
    }

    public function destroy($channelId, Thread $thread)
    {
        return $thread->unsubscribe();
    }
}
