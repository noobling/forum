<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_create_thread()
    {
        $this->actingAs($user = factory('App\User')->create());

        $thread = factory('App\Thread')->make(['user_id' => $user->id]);

        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
            ->assertStatus(200)
            ->assertSee($thread->body)
            ->assertSee($thread->title);

    }

    /** @test */
    public function a_guest_cannot_create_thread()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = factory('App\Thread')->make();

        $this->post('/threads', $thread->toArray());
    }
}
