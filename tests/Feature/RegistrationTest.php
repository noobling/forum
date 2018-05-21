<?php
/**
 * Created by PhpStorm.
 * User: FBI
 * Date: 17/04/2018
 * Time: 10:38 PM
 */

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
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
        event(new Registered(create('App\User')));
        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

    /** @test */
    public function user_can_fully_confirm_their_email()
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'David',
            'email' => 'a@a.com',
            'password' => 'asd123',
            'password_confirmation' => 'asd123'
        ]);

        $user = User::where('name', 'David')->first();

        $this->assertFalse($user->verified);
        $this->assertNotNull($user->confirmation_token);

        $this->get(route('register.confirm', ['token' => $user->confirmation_token]));

        $this->assertTrue($user->fresh()->verified);
    }

    /** @test */
    public function cannot_verify_with_invalid_token()
    {
        $this->get(route('register.confirm'), ['token' => 'invalid'])
            ->assertRedirect(route('threads'))
            ->assertSessionHas('flash', 'Unknown token');
    }
}
