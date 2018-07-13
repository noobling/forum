<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_administrator_may_lock_a_thread()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $thread->lock();

        $this->postJson($thread->path() . '/replies', [
            'body' => 'Foo bar'
        ])->assertStatus(422);
    }
}


