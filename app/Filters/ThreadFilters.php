<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ThreadFilters
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        if (! $username = $this->request->by) return $builder;

        $user = \App\User::where('name', \request('by'))->firstOrFail();
        return $builder->where('user_id', $user->id);

    }
}