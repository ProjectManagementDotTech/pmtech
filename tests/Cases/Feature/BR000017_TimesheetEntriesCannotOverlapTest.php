<?php

namespace Tests\Cases\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class BR000017_TimesheetEntriesCannotOverlapTest extends TestCase
{
    /** @test */
    public function timesheetEntriesCannotOverlap()
    {
        Log::info(__METHOD__);

        $token = $this->login('user0001@test.com', 'Welcome123');

        $response = $this->post('/api/v1/timesheet_entries', [
            'started_at' => Carbon::now()->subSeconds(10),
            'description' => 'BR000017-0001'
        ], [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(422)->assertJsonFragment([
            'message' => 'There are overlapping timesheet entries.',
            'errors' => [
                'started_at' => [
                    'There is at least one timesheet entry that overlaps ' .
                    'with this new timesheet entry.'
                ]
            ]
        ]);
    }
}
