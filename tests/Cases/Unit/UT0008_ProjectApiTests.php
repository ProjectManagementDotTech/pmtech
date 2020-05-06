<?php

namespace Tests\Cases\Unit;

use App\Events\WorkspaceUpdated;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use App\Workspace;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class UT0008_ProjectApiTests extends TestCase
{
    /** @test */
    public function createProjectInOwnedWorkspace()
    {
        Log::info(__METHOD__);

        Event::fake([
            WorkspaceUpdated::class
        ]);

        $userRepository = new UserRepository();
        $user = $userRepository->findFirstByEmail('user0001@test.com');
        $this->assertNotNull($user);
        $workspace = Workspace::where('name', 'Test0001')->first();
        $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/workspaces/' . $workspace->id .
            '/projects', [
                'abbreviation' => 'UT8',
                'name' => 'UT0008-0001'
        ]);
        $response->assertStatus(201)->assertHeader('Location');
        Event::assertDispatched(WorkspaceUpdated::class,
            function ($event) use ($workspace) {
                return $event->workspaceId == $workspace->id;
        });
        $project = ProjectRepository::byName('UT0008-0001', $workspace);
        $this->assertNotNull($project);
        $this->assertDatabaseHas('project_user', [
            'project_id' => $project->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function createProjectInNotOwnedWorkspace()
    {
        Log::info(__METHOD__);
    }

    /** @test */
    public function listWorkspaceProjects()
    {
        Log::info(__METHOD__);

        $workspace = Workspace::where('name', 'Test0001')->first();
        $token = $this->login('user0001@test.com', 'Welcome123');
        $response = $this->get('/api/v1/workspaces/' . $workspace->id .
            '/projects', [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'UT0008-0001'
            ])
            ->assertJsonCount(1);
    }
}
