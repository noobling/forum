<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

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
