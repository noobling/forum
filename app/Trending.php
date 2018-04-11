<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Trending
{
    /**
     * Get the trending threads
     *
     * @return array
     */
    public function get()
    {
        return  array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));
    }

    /**
     * @param $thread
     *
     * Push a thread onto redis
     */
    public function push($thread)
    {
        Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    public function reset()
    {
        Redis::del($this->cacheKey());
    }

    public function cacheKey()
    {
        return app()->environment('testing') ? 'testing_trending_threads': 'trending_threads';
    }
}