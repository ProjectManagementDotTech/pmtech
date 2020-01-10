<?php

namespace Tests\Feature;

use App\Repositories\TimesheetEntryRepository;
use Tests\Shared\TestCase;

class BR000009_CannotEditTimesheetEntryFromDifferentUserTest extends TestCase
{
    /** @test */
    public function updateTimesheetEntryFromDifferentUser()
    {
        $token = $this->login('user0004@test.com', 'Welcome123');

        $timesheetEntry = TimesheetEntryRepository::filter([
            'description' => 'UT0011-0004'
        ]);

        $response = $this->put('/api/v1/timesheet_entries/' .
            $timesheetEntry[0]->id, [
            'description' => 'BR000009-0001'
        ], [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(403);
    }
}
