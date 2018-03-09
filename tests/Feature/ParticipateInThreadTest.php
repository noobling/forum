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
        $this->signIn();

        $reply = make('App\Reply');

        $thread = create('App\Thread');

        $this->post($thread->path() . '/replies', $reply->toArray());
            
        $this->get($thread->path())->assertSee($reply->body);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test */
    public function replies_require_a_body()
    {
        $this->signIn();

        $reply = make('App\Reply', ['body' => null]);
        $thread = create('App\Thread');

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

}
