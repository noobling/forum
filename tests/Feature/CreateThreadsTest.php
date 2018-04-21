<?php

namespace Tests\Feature;

use App\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function an_authenticated_user_can_create_thread()
    {
        $this->signIn();

        $thread = make('App\Thread');

        $response = $this->post(route('threads'), $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertStatus(200)
            ->assertSee($thread->body)
            ->assertSee($thread->title);

    }

    /** @test */
    public function a_guest_cannot_create_thread()
    {
        $this->withExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect(route('login'));
        
        $this->post(route('threads'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_guest_cannot_see_create_thread_page()
    {
        $this->withExceptionHandling()
            ->get('/threads/create')
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_cannot_create_a_thread_without_a_verified_email()
    {
        $user = factory('App\User')->states('unconfirmed')->create();

        $this->signIn($user);
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray())
            ->assertRedirect('/threads')
            ->assertSessionHas('flash');
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function a_user_can_delete_their_own_thread()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, Activity::count());
    }

    /** @test */
    public function an_unauthorized_user_cannot_delete_the_thread()
    {
        $thread = create('App\Thread');

        $response = $this->withExceptionHandling()->delete($thread->path());

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('threads', ['id' => $thread->id]);

        $this->signIn();

        $response = $this->delete($thread->path());
        $response->assertStatus(403);
        $this->assertDatabaseHas('threads', ['id' => $thread->id]);
    }

    /**
     * HELPER METHODS
     */
    /**
     * @param array $overrides
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    public function publishThread($overrides = [])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    
    }
}
