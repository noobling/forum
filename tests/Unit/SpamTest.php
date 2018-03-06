<?php

namespace Tests\Unit;

use App\Inspections\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    function can_detect_spam()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('not spam'));
    }

    /**
     * @test
     * @throws \Exception
     */
    function it_can_detect_key_held_down()
    {
        $spam = new Spam();

        $this->expectException(\Exception::class);

        $spam->detect('aaaaa');
    }
}
