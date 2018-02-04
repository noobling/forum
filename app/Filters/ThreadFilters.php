<?php

namespace App\Filters;

class ThreadFilters extends Filters
{

    /**
     * @var array
     */
    protected $filters = ['by', 'popular', 'unanswered'];

    /**
     * Filter the query by a given username
     *
     * @param String $username
     * @return mixed
     */
    protected function by($username)
    {
        $user = \App\User::where('name', $username)->firstOrFail();
        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter query by number of replies
     *
     * @return this
     */
    protected function popular()
    {
        $this->builder->getQuery()->orders = [];
        return $this->builder->orderBy('replies_count', 'desc');
    }

    /**
     * Show only unanswered threads
     */

    protected function unanswered()
    {
        return $this->builder->where('replies_count', 0);
    }
}