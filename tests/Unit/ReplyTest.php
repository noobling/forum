<?php

namespace Tests\Unit;

use App\Reply;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function is_has_an_owner()
    {
        $reply = create('App\Reply');

        $this->assertInstanceOf('App\User', $reply->owner);
    }

    /** @test */
    function it_knows_if_it_was_published_recently()
    {
        $reply = create('App\Reply');

        $this->assertTrue($reply->wasPublishedRecently());

        $reply->created_at = Carbon::now()->subMonth();

        $this->assertFalse($reply->wasPublishedRecently());
    }

    /** @test */
    function it_add_anchors_for_mentioned_users()
    {
        $reply = new Reply(['body' => 'Hello @DavidYu.']);

        $this->assertEquals('Hello <a href="/profiles/DavidYu">@DavidYu</a>.', $reply->body);
    }
}
