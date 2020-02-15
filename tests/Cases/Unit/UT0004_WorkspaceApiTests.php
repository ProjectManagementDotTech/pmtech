<?php

namespace Tests\Cases\Unit;

use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Illuminate\Support\Facades\Auth;
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
        $this->login('user0001@test.com', 'Welcome123');
        $response = $this->get('/api/v1/workspaces');
        $response->assertStatus(200)->assertJsonCount(1)->assertJsonFragment([
            'name' => 'Test0001'
        ]);
    }

    /** @test */
    public function addWorkspace()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/workspaces', [
            'name' => 'UT0004-0001'
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

    /** @test */
    public function archiveOwnedWorkspace()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $workspace = WorkspaceRepository::filter([
            'name' => 'UT0004-0001'
        ])[0];
        $response = $this->post('/api/v1/workspaces/' . $workspace->id .
            '/archive');
        $response->assertStatus(205)->assertHeader('Location',
            route('workspaces.show', [
                'workspace' => Auth::user()->workspaces[0]->id
            ])
        );
        $this->assertSoftDeleted('workspaces', [
            'id' => $workspace->id
        ]);

        WorkspaceRepository::restore($workspace);
        $workspace->refresh();
        $this->assertNull($workspace->deleted_at);
    }

    /** @test */
    public function archiveNotOwnedWorkspace()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $user = UserRepository::byEmail('user0004@test.com');
        $workspace = $user->workspaces[0];
        $response = $this->post('/api/v1/workspaces/' . $workspace->id .
            '/archive');
        $response->assertStatus(403)->assertJsonFragment([
            'message' => 'This action is unauthorized.'
        ]);
        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id
        ]);
    }

    /** @test */
    public function deleteOwnedWorkspace()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/workspaces', [
            'name' => 'UT0004-0002'
        ]);

        $response->assertStatus(201)->assertHeader('Location');
        $this->assertDatabaseHas('workspaces', [
            'name' => 'UT0004-0002'
        ]);

        $workspace = WorkspaceRepository::filter([
            'name' => 'UT0004-0002'
        ])[0];
        $response = $this->delete('/api/v1/workspaces/' . $workspace->id);
        $response->assertStatus(205)->assertHeader('Location',
            route('workspaces.show', [
                'workspace' => Auth::user()->workspaces[0]->id
            ])
        );
        $this->assertDatabaseMissing('workspaces', [
            'name' => 'UT0004-0002'
        ]);
    }

    /** @test */
    public function deleteNotOwnedWorkspace()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $user = UserRepository::byEmail('user0004@test.com');
        $workspace = $user->workspaces[0];
        $response = $this->delete('/api/v1/workspaces/' . $workspace->id);
        $response->assertStatus(403)->assertJsonFragment([
            'message' => 'This action is unauthorized.'
        ]);
        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id
        ]);
    }
}
