<?php

namespace Tests\Cases\BusinessRequirements;

use App\Repositories\ProjectRepository;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class BR000008_TimesheetEntryCreationAgainstWorkspaceOrProjectOrTaskTest extends
    TestCase
{
    /** @test */
    public function createTimesheetEntryAgainstWorkspace()
    {
        Log::info(__METHOD__);

        $workspace = WorkspaceRepository::filter([
            'name' => 'Test0001'
        ])[0];
        $user = UserRepository::byEmail('user0001@test.com');
        $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/timesheet_entries', [
            'workspace_id' => $workspace->id,
            'description' => 'BR000008-0001'
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('timesheet_entries', [
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'description' => 'BR000008-0001'
        ]);

        sleep(3);
    }

    /** @test */
    public function createTimesheetEntryAgainstProject()
    {
        Log::info(__METHOD__);

        $user = UserRepository::byEmail('user0001@test.com');
        $workspace = WorkspaceRepository::filter([
            'name' => 'Test0001'
        ])[0];
        $project = ProjectRepository::byName('UT0008-0001', $workspace);
        $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/timesheet_entries', [
            'project_id' => $project->id,
            'workspace_id' => $workspace->id,
            'description' => 'BR000008-0002'
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('timesheet_entries', [
            'project_id' => $project->id,
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'description' => 'BR000008-0002'
        ]);

        sleep(3);
    }

    /** @test */
    public function createTimesheetEntryAgainstTask()
    {
        Log::info(__METHOD__);

        $user = UserRepository::byEmail('user0001@test.com');
        $workspace = WorkspaceRepository::filter([
            'name' => 'Test0001'
        ])[0];
        $project = ProjectRepository::byName('UT0008-0001', $workspace);
        $task = TaskRepository::byName('UT0010-0001', $project);
        $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/timesheet_entries', [
            'task_id' => $task->id,
            'workspace_id' => $workspace->id,
            'description' => 'BR000008-0003'
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('timesheet_entries', [
            'project_id' => $project->id,
            'task_id' => $task->id,
            'user_id' => $user->id,
            'workspace_id' => $workspace->id,
            'description' => 'BR000008-0003'
        ]);

        sleep(3);
    }
}
