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

}
