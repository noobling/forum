<?php

namespace Tests\Unit;

use App\Spam;
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
}
