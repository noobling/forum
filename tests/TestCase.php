<?php

namespace Tests;

use App\Exceptions\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * Creates and signs in a user
     * 
     * @param App\User $user
     * @return 
     */
    protected function signIn($user = null)
    {
        $user = $user ?: create('App\User');
        
        $this->actingAs($user);

        return $this;
    }
}
