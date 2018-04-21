<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mockery\Exception;

class RegisterConfirmationController extends Controller
{
    public function index()
    {
        try {
            User::where('confirmation_token', request('token'))
                ->firstOrFail()
                ->update(['verified' => true]);

        } catch (\Exception $e) {
            return redirect('/threads')
                ->with('flash', 'Unknown token');
        }

        return redirect('/threads')
            ->with('flash', 'Your email has been confirmed');
    }
}
