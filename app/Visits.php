<?php

namespace App;

use Illuminate\Support\Facades\Redis;

class Visits
{
    protected $thread;

    /**
     * Visits constructor.
     * @param $thread
     */
    public function __construct($thread)
    {
        $this->thread = $thread;
    }

    /**
     * Record the visit
     *
     * @return mixed
     */
    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this->thread;
    }

    /**
     * Get the visit count
     *
     * @return mixed
     */
    public function count()
    {
        return Redis::get($this->cacheKey())?: 0;
    }

    /**
     * Delete all visits
     *
     * @return mixed
     */
    public function reset()
    {
        Redis::del($this->cacheKey());

        return $this->thread;
    }

    /**
     * Get visits id for thread
     *
     * @return string
     */
    public function cacheKey()
    {
        return "threads.{$this->thread->id}.visits";
    }
}