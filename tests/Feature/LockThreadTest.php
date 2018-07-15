<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LockThreadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cannot_add_a_new_reply_when_a_thread_is_locked()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $thread->lock();

        $this->postJson($thread->path() . '/replies', [
            'body' => 'Foo bar'
        ])->assertStatus(422);
    }

    /** @test */
    function a_non_admin_cannot_lock_a_thread()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread');

        $this->post(route('lock-thread.store', $thread))->assertStatus(403);

        $this->assertFalse(!!$thread->fresh()->locked);
    }

    /** @test */
    function an_admin_can_lock_a_thread()
    {
        $this->signIn(factory('App\User')->states('administrator')->create());

        $thread = create('App\Thread');

        $this->post(route('lock-thread.store', $thread))->assertStatus(200);

        $this->assertTrue(!!$thread->fresh()->locked, 'Failed asserting the thread was locked');
    }
}


