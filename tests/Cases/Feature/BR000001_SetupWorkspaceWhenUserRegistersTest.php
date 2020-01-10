<?php

namespace Tests\Cases\Feature;

use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\Shared\TestCase;

class BR000001_SetupWorkspaceWhenUserRegistersTest extends TestCase
{
    /** @test */
    public function registerNewUserAndVerifyThatWorkspaceWasCreatedAfterEmailVerification()
    {
        Log::info(__METHOD__);

        Mail::fake();

        $this->post('/api/v1/register', [
            'name' => 'Business Requirement 000001',
            'email' => 'br000001@test.com',
            'password' => 'Welcome123',
            'password_confirmation' => 'Welcome123'
        ]);

        $cacheValue = Cache::store('database')->get('br000001@test.com');
        $user = User::where('email', 'br000001@test.com')->first();

        $this->assertDatabaseMissing('workspaces', [
            'owner_user_id' => $user->id,
            'name' => 'Default'
        ]);

        $response = $this->get('/email/verify/' . $user->id . '/' .
            $cacheValue);
        $response->assertStatus(302);

        $this->assertDatabaseHas('workspaces', [
            'owner_user_id' => $user->id,
            'name' => 'Default'
        ]);
    }
}
