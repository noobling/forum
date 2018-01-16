<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadsTest extends TestCase
{
    use RefreshDatabase;
    
    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }

    /** @test */
    public function a_user_can_browse_threads()
    {
        $this->get('/threads')
            ->assertStatus(200)
            ->assertSee($this->thread->title);

        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_view_replies_on_a_thread()
    {
        $reply = create('App\Reply',['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())
            ->assertSee($reply->body);

    }

    /** @test */
    function a_user_can_filter_threads_with_a_channel()
    {
        $channel = create('App\Channel');

        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');

        $this->get("/threads/{$channel->slug}")
            ->assertSee($threadInChannel->body)
            ->assertDontSee($threadNotInChannel->body);
    }

    /** @test */
    function a_user_can_filter_threads_by_user()
    {
        $this->signIn(create('App\User', ['name' => 'johndoe']));

        $johndoeThread = create('App\Thread', ['user_id' => auth()->id()]);
        $notJohndoeThread = create('App\Thread');

        $this->get('threads?by=johndoe')
            ->assertSee($johndoeThread->title)
            ->assertDontSee($notJohndoeThread->title);
    }
}
