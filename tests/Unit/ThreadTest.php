<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    protected $thread;

    public function setUp()
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }

    /** @test */
    function a_thread_can_make_a_url_string_path()
    {
        $thread = create('App\Thread');

        $this->assertEquals(
            "/threads/{$thread->channel->slug}/{$thread->id}",
            $thread->path()
        );
    }

    /** @test */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    public function a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->creator);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => '1'
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    public function a_thread_has_subscriptions()
    {
        $thread = create('App\Thread');

        // could do $user->subscribe($thread)
        // But users can do anything and so it can contain a lot of code
        // becoming a `God object`

        $thread->subscribe($userId = 1);

        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId=1)->count());
    }

    /** @test */
    public function a_thread_can_be_unsubscribe_to()
    {
        $thread = create('App\Thread');

        $thread->unsubscribe($userId = 1);

        $this->assertEquals(0, $thread->subscriptions()->where('user_id', $userId = 1)->count());

    }

    /** @test */
    public function it_can_show_if_signed_in_user_is_subscribed_to_the_thread()
    {
        $thread = create('App\Thread');

        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->subscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    public function a_thread_notifies_all_subscribers_when_a_reply_is_added()
    {
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    function a_thread_can_determine_if_there_are_updates_for_user()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $this->assertTrue($thread->hasUpdatesFor(auth()->user()));

        auth()->user()->read($thread);

        $this->assertFalse($thread->hasUpdatesFor(auth()->user()));
    }

    /** @test */
    function a_thread_can_record_each_visit()
    {
        $thread = create('App\Thread');

        $thread->resetVisits();

        $this->assertSame(0, $thread->visits());

        $thread->recordVisit();

        $this->assertEquals(1, $thread->visits());

        $thread->recordVisit();

        $this->assertEquals(2, $thread->visits());
    }
}
