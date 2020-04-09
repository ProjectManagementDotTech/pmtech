<?php

namespace Tests\Cases\BusinessRequirements;

use App\Repositories\TimesheetEntryRepository;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class BR000009_CannotEditTimesheetEntryFromDifferentUserTest extends TestCase
{
    /** @test */
    public function updateTimesheetEntryFromDifferentUser()
    {
        Log::info(__METHOD__);

        $this->login('user0004@test.com', 'Welcome123');

        $timesheetEntry = TimesheetEntryRepository::filter([
            'description' => 'UT0011-0004'
        ]);

        $response = $this->put('/api/v1/timesheet_entries/' .
            $timesheetEntry[0]->id, [
            'description' => 'BR000009-0001'
        ]);
        $response->assertStatus(403);
    }
}
