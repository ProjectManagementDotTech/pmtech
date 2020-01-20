<?php

namespace Tests\Cases\Feature;

use App\Repositories\TimesheetEntryRepository;
use App\Repositories\UserRepository;
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
        $token = $this->login('user0001@test.com', 'Welcome123');

        $response = $this->post('/api/v1/timesheet_entries', [
            'started_at' => Carbon::now()->addSeconds(2),
            'description' => 'BR000018-0001'
        ], [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(201);
        $timesheetEntries = TimesheetEntryRepository::filter([
            'user_id' => $user->id,
            'ended_at' => NULL
        ]);
        $this->assertEquals(1, count($timesheetEntries));
    }
}
