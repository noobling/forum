<?php

namespace Tests\Feature;

use App\Trending;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrendingThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->trending = new Trending();

        $this->trending->reset();
    }

    /** @test */
    function it_can_increment_a_trending_thread_count()
    {
        $this->assertEmpty(Redis::zrevrange($this->trending->cacheKey(), 0, -1));

        $thread = create('App\Thread');
        $this->call('GET', $thread->path());

        $this->assertCount(1, Redis::zrevrange($this->trending->cacheKey(), 0, -1));
    }
}
