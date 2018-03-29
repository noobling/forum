<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_user_can_get_last_reply()
    {
        $user = create('App\User');
        $reply = create('App\Reply', ['user_id' => $user->id]);

        $this->assertEquals($reply->id, $user->lastReply->id);
    }

    /** @test */
    function a_user_can_get_their_avatar()
    {
        $user = create('App\User');

        $this->assertEquals('/avatars/default.jpg', $user->avatar());

        $user->avatar_path = '/avatar.jpg';

        $this->assertEquals('/avatar.jpg', $user->avatar());
    }
}