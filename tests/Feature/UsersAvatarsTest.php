<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersAvatarsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_only_allows_members_to_upload_avatars()
    {
        $this->withExceptionHandling();

        $response = $this->json('POST', '/api/users/1/avatars');

        $response->assertStatus(401);
    }

    /** @test */
    public function a_valid_avatar_must_be_provided()
    {
        $this->signIn();

        $this->withExceptionHandling();

        $this->json('POST', '/api/users/' . auth()->id() . '/avatars', [
            'avatar' => 'not an avatar'
        ])
            ->assertStatus(422);
    }

    /** @test */
    function a_user_can_add_an_avatar_to_their_profile()
    {
        $this->signIn();

        // fake the public disk
        Storage::fake('public');

        $this->json('POST', '/api/users/' . auth()->id() . '/avatars', [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
        ]);

        $this->assertEquals('avatars/' . $file->hashName(), auth()->user()->avatar_path);

        Storage::disk('public')->assertExists('/avatars/' . $file->hashName());
    }
}
