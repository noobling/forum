<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

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

    /** @test */
    function a_user_can_filter_threads_by_popularity()
    {
        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWith0Replies = $this->thread;

        $response = $this->getJson('threads?popular=1')->json();

        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
    }

    /** @test */
    function a_user_can_filter_threads_by_answered()
    {
        $thread = create('App\Thread');

        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->getJson('threads?unanswered=1')->json();

        $this->assertCount(1, $response);

    }

    /** @test */
    function an_unauthorized_user_cannot_delete_reply()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->delete('/replies/' . $reply->id)
            ->assertRedirect('/login');

        $this->signIn();
        $this->delete('/replies/' . $reply->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('replies', ['id' => $reply->id]);
    }

    /** @test */
    function authorized_users_can_delete_reply()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete('/replies/' . $reply->id)
            ->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    function authorized_users_can_update_replies()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        $replyBody = 'Changed the body';
        $this->patch('/replies/' . $reply->id, ['body' => $replyBody]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $replyBody]);
    }

    /** @test */
    function unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');
        $replyBody = 'Changed the body';
        $this->patch('/replies/' . $reply->id, ['body' => $replyBody])
            ->assertRedirect('/login');

        $this->signIn();
        $this->patch('/replies/' . $reply->id, ['body' => $replyBody])
            ->assertStatus(403);

    }

    /** @test */
    function a_user_can_get_a_list_of_replies()
    {
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id], 2);

        $response = $this->getJson($thread->path() . '/replies')->json();
        $this->assertEquals(2, $response['total']);
    }

    /** @test */
    function a_user_cannot_add_spam_reply()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => 'spammer'
        ]);

        $this->expectException(\Exception::Class);

        $this->post($thread->path() . '/replies', $reply->toArray(0));

    }
}
