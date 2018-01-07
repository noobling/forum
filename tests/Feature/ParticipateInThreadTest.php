<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInThreadTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function authenticated_user_can_create_a_reply_to_thread()
    {
        $this->be($user = factory('App\User')->create());

        $reply = factory('App\Reply')->make();

        $thread = factory('App\Thread')->create();

        $this->post($thread->path() . '/replies', $reply->toArray());
            
        $this->get($thread->path())->assertSee($reply->body);
    }
}
