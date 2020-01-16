<?php

namespace Tests\Cases\Unit;

use App\Project;
use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class UT0010_TaskApiTests extends TestCase
{
    /** @test */
    public function createTaskInOwnedProject()
    {
        Log::info(__METHOD__);

        $user = UserRepository::byEmail('user0001@test.com');
        $project = ProjectRepository::byName('UT0008-0001',
            $user->ownedWorkspaces[0]);
        $token = $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/projects/' . $project->id . '/tasks', [
            'name' => 'UT0010-0001'
        ], [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(201)->assertHeader('Location');
    }

    /** @test */
    public function createTaskInNotOwnedProject()
    {
        Log::info(__METHOD__);
    }

    /** @test */
    public function listProjectTasks()
    {
        Log::info(__METHOD__);

        $user = UserRepository::byEmail('user0001@test.com');
        $project = ProjectRepository::byName('UT0008-0001',
            $user->ownedWorkspaces[0]);
        $token = $this->login('user0001@test.com', 'Welcome123');
        $response = $this->get('/api/v1/projects/' . $project->id . '/tasks', [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'UT0010-0001'
            ])
            ->assertJsonCount(1);
    }
}
