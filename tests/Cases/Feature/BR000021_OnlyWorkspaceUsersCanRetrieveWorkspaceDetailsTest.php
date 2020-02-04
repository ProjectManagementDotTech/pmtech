<?php

namespace Tests\Cases\Feature;

use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class BR000021_OnlyWorkspaceUsersCanRetrieveWorkspaceDetailsTest extends TestCase
{
    /** @test */
    public function retrieveWorkspaceInformationWithAuthorizedUser()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');

        $user = UserRepository::byEmail('user0001@test.com');
        $workspace = WorkspaceRepository::filter([
            'name' => 'Test0001'
        ])[0];
        $response = $this->get('/api/v1/workspaces/' . $workspace->id);
        $response->assertStatus(200)->assertJsonFragment([
            'name' => 'Test0001'
        ]);
    }

    /** @test */
    public function retrieveWorkspaceInformationWithUnauthorizedUser()
    {
        Log::info(__METHOD__);

        $token = $this->login('user0001@test.com', 'Welcome123');

        $user = UserRepository::byEmail('user0004@test.com');
        $workspace = $user->ownedWorkspaces[0];
        $response = $this->get('/api/v1/workspaces/' . $workspace->id, [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(403);
    }
}
