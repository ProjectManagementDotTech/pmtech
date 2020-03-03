<?php

namespace Tests\Feature;

use App\Mail\AccountActivation;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\Shared\TestCase;

class FT0005_RegisterNewUserTwiceWithoutVerificationTest extends TestCase
{
    /** @test */
    public function registerNewUser()
    {
        Log::info(__METHOD__);

        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Feature Test User 0004',
            'email' => 'ft000001@test.com',
            'password' => 'Welcome123',
            'password_confirmation' => 'Welcome123'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('', $response->getContent());
        $user = User::where('email', 'ft000001@test.com')->first();
        $this->assertNotNull($user);

        Mail::assertSent(AccountActivation::class, function ($mail) use ($user) {
            return $mail->user()->id == $user->id;
        });
        $this->assertDatabaseHas('cache', [
            'key' => 'project_managementtech_cacheft000001@test.com'
        ]);
    }

    /** @test */
    public function registerNewUserAgain()
    {
        Log::info(__METHOD__);

        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Feature Test User 0004',
            'email' => 'ft000001@test.com',
            'password' => 'Welcome123',
            'password_confirmation' => 'Welcome123'
        ]);

        $response->assertNoContent(200);
        $user = User::where('email', 'ft000001@test.com')->first();
        $this->assertNotNull($user);

        Mail::assertSent(AccountActivation::class, function ($mail) use ($user) {
            return $mail->user()->id == $user->id;
        });
        $this->assertDatabaseHas('cache', [
            'key' => 'project_managementtech_cacheft000001@test.com'
        ]);
    }
}
