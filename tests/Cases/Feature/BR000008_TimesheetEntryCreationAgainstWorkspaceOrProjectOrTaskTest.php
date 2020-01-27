<?php

namespace Tests\Cases\Feature;

use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class BR000008_TimesheetEntryCreationAgainstWorkspaceOrProjectOrTaskTest extends
    TestCase
{
    /** @test */
    public function createTimesheetEntryAgainstWorkspace()
    {
        Log::info(__METHOD__);

        $user = UserRepository::byEmail('user0001@test.com');
        $token = $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/timesheet_entries', [
            'description' => 'BR000008-0001'
        ], [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('timesheet_entries', [
            'user_id' => $user->id,
            'workspace_id' => $user->ownedWorkspaces[0]->id,
            'description' => 'BR000008-0001'
        ]);

        sleep(3);
    }

    /** @test */
    public function createTimesheetEntryAgainstProject()
    {
        Log::info(__METHOD__);

        $user = UserRepository::byEmail('user0001@test.com');
        $project = ProjectRepository::byName('UT0008-0001',
            $user->ownedWorkspaces[0]);
        $token = $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/timesheet_entries', [
            'project_id' => $project->id,
            'description' => 'BR000008-0002'
        ], [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('timesheet_entries', [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'workspace_id' => $user->ownedWorkspaces[0]->id,
            'description' => 'BR000008-0002'
        ]);

        sleep(3);
    }

    /** @test */
    public function createTimesheetEntryAgainstTask()
    {
        Log::info(__METHOD__);

        $user = UserRepository::byEmail('user0001@test.com');
        $project = ProjectRepository::byName('UT0008-0001',
            $user->ownedWorkspaces[0]);
        $task = TaskRepository::byName('UT0010-0001', $project);
        $token = $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/timesheet_entries', [
            'task_id' => $task->id,
            'description' => 'BR000008-0003'
        ], [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('timesheet_entries', [
            'project_id' => $project->id,
            'task_id' => $task->id,
            'user_id' => $user->id,
            'workspace_id' => $user->ownedWorkspaces[0]->id,
            'description' => 'BR000008-0003'
        ]);

        sleep(3);
    }
}
