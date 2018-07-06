<?php

namespace Tests\Feature;

use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BestReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_thread_creator_may_mark_reply_as_best()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $replies = create('App\Reply', ['thread_id' => $thread], 2);

        $this->assertFalse($replies[1]->isBest());

        $this->postJson(route('best-replies.store', ['best-reply' => $replies[1]]));

        $this->assertTrue($replies[1]->fresh()->isBest());
    }

    /** @test */
    function only_a_thread_creator_can_mark_reply_as_best()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $replies = create('App\Reply', ['thread_id' => $thread->id], 2);

        $this->signIn(create('App\User'));

        $this->postJson(route('best-replies.store', ['best-reply' => $replies[1]]))->assertStatus(403);

        $this->assertFalse($replies[1]->isBest());
    }

    /** @test */
    function when_a_best_reply_is_deleted_the_thread_should_be_updated_correctly()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $reply->thread->update(['best_reply_id' => $reply->id]);

        $this->assertEquals(Thread::first()->best_reply_id, $reply->id);

        $this->deleteJson(route('replies.destroy', $reply));

        $this->assertNull($reply->thread->fresh()->best_reply_id);
    }
}