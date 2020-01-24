<?php

namespace Tests\Unit;

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

        $token = $this->login('user0001@test.com', 'Welcome123');
        $response = $this->get('/api/v1/timesheet_entries/export', [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(200);
    }
}
