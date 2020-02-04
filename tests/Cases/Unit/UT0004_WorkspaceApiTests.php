<?php

namespace Tests\Cases\Unit;

use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class UT0004_WorkspaceApiTests extends TestCase
{
    /** @test */
    public function listWorkspacesBeforeLogin()
    {
        $response = $this->get('/api/v1/workspaces');
        $response->assertStatus(401)->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    /** @test */
    public function listWorkspacesAfterLogin()
    {
        $token = $this->login('user0001@test.com', 'Welcome123');
        $response = $this->get('/api/v1/workspaces', [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(200)->assertJsonCount(1)->assertJsonFragment([
            'name' => 'Test0001'
        ]);
    }

    /** @test */
    public function addWorkspace()
    {
        Log::info(__METHOD__);

        $token = $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/workspaces', [
            'name' => 'UT0004-0001'
        ], [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);

        $response->assertStatus(201)->assertHeader('Location');
        $this->assertDatabaseHas('workspaces', [
            'name' => 'UT0004-0001'
        ]);
        $user = UserRepository::byEmail('user0001@test.com');
        $this->assertNotNull($user);
        $workspace = $user->ownedWorkspaces()
            ->where('name', 'UT0004-0001')
            ->first();
        $this->assertNotNull($workspace);
        $this->assertDatabaseHas('user_workspace', [
            'user_id' => $user->id,
            'workspace_id' => $workspace->id
        ]);
    }
}
