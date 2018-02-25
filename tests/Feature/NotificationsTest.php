<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_notification_is_triggered_when_a_different_user_adds_reply_to_thread()
    {
        $this->signIn();

        $thread = create('App\Thread')->subscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'good'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'good body'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    public function a_user_can_view_unread_notifications()
    {
        $this->signIn();

        $thread = create('App\Thread')->subscribe();

        $user = auth()->user();

        $this->assertCount(0, $user->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'good body'
        ]);

        $response = $this->getJson("/profiles/{$user->name}/notifications")->json();
        $this->assertCount(1, $response);
    }

    /** @test */
    public function a_user_can_clear_notifications()
    {
        $this->signIn();

        $thread = create('App\Thread')->subscribe();

        $user = auth()->user();

        $this->assertCount(0, $user->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'good body'
        ]);

        $this->assertCount(1, $user->fresh()->unreadNotifications);

        $notificationId = $user->unreadNotifications->first()->id;

        $this->delete("/profiles/{$user->name}/notifications/{$notificationId}");

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
