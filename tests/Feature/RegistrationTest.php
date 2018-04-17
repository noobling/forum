<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 17/04/2018
 * Time: 10:38 PM
 */

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_confirmation_email_is_sent_upon_registration()
    {
        Mail::fake();
        event(new Registered(create('App\user')));
        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function user_can_fully_confirm_their_email()
    {
        $this->post('/register', [
            'name' => 'David',
            'email' => 'a@a.com',
            'password' => 'asd123',
            'password_confirmation' => 'asd123'
        ]);

        $user = User::where('name', 'David')->first();

        $this->assertFalse($user->verified);
    }
}
