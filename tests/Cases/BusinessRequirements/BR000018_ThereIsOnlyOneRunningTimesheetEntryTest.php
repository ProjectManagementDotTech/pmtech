<?php

namespace Tests\Cases\BusinessRequirements;

use App\Repositories\TimesheetEntryRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class BR000018_ThereIsOnlyOneRunningTimesheetEntryTest extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userRepository = new UserRepository();
        $this->workspaceRepository =
            new WorkspaceRepository($this->userRepository);
    }

    /** @test */
    public function makeSureThereIsOnlyOneRunningTimesheetEntry()
    {
        Log::info(__METHOD__);

        $user = $this->userRepository->findFirstByEmail('user0001@test.com');
        $workspace = $this->workspaceRepository->findFirstByName('Test0001');
        $this->login('user0001@test.com', 'Welcome123');

        $response = $this->post('/api/v1/timesheet_entries', [
            'workspace_id' => $workspace->id,
            'description' => 'BR000018-0001',
            'started_at' => Carbon::now()->addSeconds(2)
        ]);
        $response->assertStatus(201);
        $timesheetEntries = TimesheetEntryRepository::filter([
            'user_id' => $user->id,
            'ended_at' => NULL
        ]);
        $this->assertEquals(1, count($timesheetEntries));
    }

    protected $userRepository;
    protected $workspaceRepository;
}
