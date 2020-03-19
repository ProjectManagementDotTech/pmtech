<?php

namespace Tests\Cases\Unit;

use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class UT0004_WorkspaceApiTests extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userRepository = new UserRepository();
    }

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
        ])->assertHeader('etag');
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
        $user = $this->userRepository->findByEmail('user0001@test.com');
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
        $response = $this->get('/api/v1/workspaces/' . $workspace->id);
        $etag = $response->headers->get('etag');
        $response = $this->post('/api/v1/workspaces/' . $workspace->id .
            '/archive', [], [
                'If-Match' => $etag
        ]);
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
        $user = $this->userRepository->findByEmail('user0004@test.com');
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
        $response = $this->delete('/api/v1/workspaces/' . $workspace->id, [], [
            'If-Match' => $workspace->eTag()
        ]);
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
        $user = $this->userRepository->findByEmail('user0004@test.com');
        $workspace = $user->workspaces[0];
        $response = $this->delete('/api/v1/workspaces/' . $workspace->id);
        $response->assertStatus(403)->assertJsonFragment([
            'message' => 'This action is unauthorized.'
        ]);
        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id
        ]);
    }

    /** @test */
    public function editOwnedWorkspace()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $workspace = WorkspaceRepository::filter([
            'name' => 'UT0004-0001'
        ])[0];
        $response = $this->put('/api/v1/workspaces/' . $workspace->id, [
            'name' => 'UT0004-0003'
        ], [
            'If-Match' => $workspace->eTag()
        ]);
        $response->assertStatus(204)->assertNoContent();
        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id,
            'name' => 'UT0004-0003'
        ]);

        $workspace->refresh();
        $workspace->name = 'UT0004-0001';
        $workspace->save();
    }

    /** @test */
    public function giveWorkspaceAlreadyUsedName()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $workspace = WorkspaceRepository::filter([
            'name' => 'UT0004-0001'
        ])[0];
        $response = $this->put('/api/v1/workspaces/' . $workspace->id, [
            'name' => 'Test0001'
        ], [
            'If-Match' => $workspace->eTag()
        ]);
        $response->assertStatus(422)->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'name' => [
                    'The given value is already in use for name.'
                ]
            ]
        ]);
        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id,
            'name' => 'UT0004-0001'
        ]);
    }

    /** @test */
    public function editNotOwnedWorkspace()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $user = $this->userRepository->findByEmail('user0004@test.com');
        $workspace = $user->workspaces[0];
        $response = $this->put('/api/v1/workspaces/' . $workspace->id, [
            'name' => 'UT0004-0004'
        ]);
        $response->assertStatus(403)->assertJsonFragment([
            'message' => 'This action is unauthorized.'
        ]);
        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id,
            'name' => $workspace->name
        ]);
    }

    /** @test */
    public function listWorkspaceMembers()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $workspace = WorkspaceRepository::filter(['name' => 'UT0004-0001'])[0];
        $response = $this->get('/api/v1/workspaces/' . $workspace->id .
            '/members');
        $response->assertStatus(200)->assertJsonCount(1);
    }

    protected $userRepository;
}
