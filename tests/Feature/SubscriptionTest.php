<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriptionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_subscribe_to_a_thread()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $this->post($thread->path().'/subscribe');

//        $thread->addReply([
//            'user_id' => auth()->user()->id(),
//            'body' => 'good'
//        ]);

        $this->assertCount(1, $thread->subscriptions);
    }

    /** @test */
    function a_user_can_unsubscribe_to_a_thread()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $this->post($thread->path().'/subscribe');

        $this->delete($thread->path().'/subscribe');

        $this->assertCount(0, $thread->subscriptions);
    }
}