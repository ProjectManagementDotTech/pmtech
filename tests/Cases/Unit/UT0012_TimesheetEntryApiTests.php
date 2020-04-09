<?php

namespace Tests\Cases\Unit;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class UT0012_TimesheetEntryApiTests extends TestCase
{
    /** @test */
    public function createTimesheetEntryAgainstNotOwnedWorkspace()
    {
        Log::info(__METHOD__);
    }

    /** @test */
    public function createTimesheetEntryAgainstNotOwnedProject()
    {
        Log::info(__METHOD__);
    }

    /** @test */
    public function createTimesheetEntryAgainstTaskInNotOwnedProject()
    {
        Log::info(__METHOD__);
    }

    /** @test */
    public function endTimesheetEntry()
    {
        Log::info(__METHOD__);
    }

    /** @test */
    public function exportTimesheet()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $workspace = Auth::user()->workspaces[0];
        $response = $this->get('/api/v1/workspaces/' . $workspace->id .
            '/timesheet_entries/export');
        $response->assertStatus(200);

        sleep(3);
    }
}
