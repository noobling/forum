<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_mention_another_user_when_replying_to_a_thread()
    {
        $jane = create('App\User', ['name' => 'jane']);

        $this->signIn();

        $thread = create('App\Thread');

        $this->postJson($thread->path() . '/replies', ['body' => '@jane mentioned you']);

        $this->assertCount(1, $jane->notifications);
    }
}
