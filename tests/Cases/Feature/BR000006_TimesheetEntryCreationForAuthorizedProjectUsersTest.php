<?php

namespace Tests\Cases\Feature;

use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use App\Workspace;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class BR000006_TimesheetEntryCreationForAuthorizedProjectUsersTest extends
    TestCase
{
    /** @test */
    public function createTimesheetEntryForAuthorizedProjectUser()
    {
        Log::info(__METHOD__);

        $workspace = Workspace::where('name', 'Test0001')->first();
        $project = ProjectRepository::byName('UT0008-0001', $workspace);
        $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/timesheet_entries', [
            'project_id' => $project->id,
            'workspace_id' => $workspace->id,
            'description' => 'BR000006-0001'
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('timesheet_entries', [
            'description' => 'BR000006-0001'
        ]);

        sleep(3);
    }

    /** @test */
    public function createTimesheetEntryForUnauthorizedProjectUser()
    {
        Log::info(__METHOD__);

        $workspace = Workspace::where('name', 'Test0001')->first();
        $project = ProjectRepository::byName('UT0007-0001', $workspace);
        $token = $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/timesheet_entries', [
            'project_id' => $project->id,
            'description' => 'BR000006-0002'
        ], [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(403)->assertJson([
            'message' => 'This action is unauthorized.'
        ]);
        $this->assertDatabaseMissing('timesheet_entries', [
            'description' => 'BR000006-0002'
        ]);
    }
}
