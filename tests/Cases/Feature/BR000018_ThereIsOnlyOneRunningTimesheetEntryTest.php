<?php

namespace Tests\Cases\Feature;

use App\Repositories\TimesheetEntryRepository;
use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class BR000018_ThereIsOnlyOneRunningTimesheetEntryTest extends TestCase
{
    /** @test */
    public function makeSureThereIsOnlyOneRunningTimesheetEntry()
    {
        Log::info(__METHOD__);

        $user = UserRepository::byEmail('user0001@test.com');
        $workspace = WorkspaceRepository::filter([
            'name' => 'Test0001'
        ])[0];
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
}
