<?php

namespace Tests\Cases\Unit;

use App\Mail\AccountActivation;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\Shared\TestCase;

class UT0002_UserApiTests extends TestCase
{
    /** @test */
    public function loginViaApi()
    {
        Log::info(__METHOD__);

        $user = User::where('email', 'user0001@test.com')->first();

        $response = $this->post('/api/v1/login', [
            'email' => $user->email,
            'password' => 'Welcome123'
        ]);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'token_type' => 'Bearer'
            ]);
    }

    /** @test */
    public function loginViaApiWithWrongPassword()
    {
        Log::info(__METHOD__);

        $user = User::where('email', 'user0001@test.com')->first();

        $response = $this->post('/api/v1/login', [
            'email' => $user->email,
            'password' => 'Welcome124'
        ]);
        $response->assertStatus(422)
            ->assertJsonFragment([
                'errors' => [
                    'email' => [
                        'These credentials do not match our records.'
                    ]
                ],
                'message' => 'The given data was invalid.'
            ]);
    }

    /** @test */
    public function retrieveSelfAfterLogin()
    {
        Log::info(__METHOD__);

        $token = $this->login('user0001@test.com', 'Welcome123');
        $response = $this->get('/api/v1/user', [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'email' => 'user0001@test.com',
                'name' => 'Test User 0001'
            ]);
    }

    /** @test */
    public function registerNewUser()
    {
        Log::info(__METHOD__);

        Mail::fake();

        $response = $this->post('/api/v1/register', [
            'name' => 'Unit Test User 0004',
            'email' => 'user0004@test.com',
            'password' => 'Welcome123',
            'password_confirmation' => 'Welcome123'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('', $response->getContent());
        $user = User::where('email', 'user0004@test.com')->first();
        $this->assertNotNull($user);

        Mail::assertSent(AccountActivation::class, function ($mail) use ($user) {
            return $mail->user()->id == $user->id;
        });
        $this->assertDatabaseHas('cache', [
            'key' => 'project_managementtech_cacheuser0004@test.com'
        ]);
    }

    /** @test */
    public function loginBeforeVerifyingEmailAddress()
    {
        Log::info(__METHOD__);

        $user = User::where('email', 'user0004@test.com')->first();

        $response = $this->post('/api/v1/login', [
            'email' => $user->email,
            'password' => 'Welcome123'
        ]);
        $response->assertStatus(403)
            ->assertJsonFragment([
                'message' => 'Your email address is not verified.'
            ]);
    }

    /** @test */
    public function verifyEmailAddress()
    {
        Log::info(__METHOD__);

        $user = User::where('email', 'user0004@test.com')->first();
        $cacheValue = Cache::store('database')->get('user0004@test.com');

        $response = $this->get('/email/verify/' . $user->id . '/' .
            $cacheValue);
        $response->assertStatus(302);
    }

    /** @test */
    public function loginAfterVerifyingEmailAddress()
    {
        Log::info(__METHOD__);

        $user = User::where('email', 'user0004@test.com')->first();

        $response = $this->post('/api/v1/login', [
            'email' => $user->email,
            'password' => 'Welcome123'
        ]);
        $response->assertStatus(200);
        $jsonObj = json_decode($response->getContent());
        $token = [
            'token' => $jsonObj->access_token,
            'type' => $jsonObj->token_type,
        ];
        $response = $this->get('/api/v1/user', [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'email' => 'user0004@test.com',
                'name' => 'Unit Test User 0004'
            ]);
    }

    /** @test */
    public function registerNewUserWithoutFakingEmails()
    {
        Log::info(__METHOD__);

        $response = $this->post('/api/v1/register', [
            'name' => 'Unit Test User 0005',
            'email' => 'user0005@test.com',
            'password' => 'Welcome123',
            'password_confirmation' => 'Welcome123'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('', $response->getContent());
        $user = User::where('email', 'user0005@test.com')->first();
        $this->assertNotNull($user);

        $this->assertDatabaseHas('cache', [
            'key' => 'project_managementtech_cacheuser0005@test.com'
        ]);
    }
}
