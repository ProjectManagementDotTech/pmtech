<?php

namespace Tests\Cases\BusinessRequirements;

use App\Repositories\ProjectRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class BR000007_TimesheetEntryCreationForAuthorizedWorkspaceUsersTest extends
    TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userRepository = new UserRepository();
    }

    /** @test */
    public function createTimesheetEntryForAuthorizedWorkspaceUser()
    {
        Log::info(__METHOD__);

        $user = $this->userRepository->findByEmail('user0001@test.com');
        $workspace = $user->ownedWorkspaces[0];
        $token = $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/timesheet_entries', [
            'workspace_id' => $workspace->id,
            'description' => 'BR000007-0001'
        ], [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('timesheet_entries', [
            'description' => 'BR000007-0001'
        ]);

        sleep(3);
    }

    /** @test */
    public function createTimesheetEntryForUnauthorizedProjectUser()
    {
        Log::info(__METHOD__);

        $user = $this->userRepository->findByEmail('user0004@test.com');
        $workspace = $user->ownedWorkspaces[0];
        $token = $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/timesheet_entries', [
            'workspace_id' => $workspace->id,
            'description' => 'BR000007-0002'
        ], [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(403)->assertJson([
            'message' => 'This action is unauthorized.'
        ]);
        $this->assertDatabaseMissing('timesheet_entries', [
            'description' => 'BR000007-0002'
        ]);
    }

    protected $userRepository;
}
