<?php

namespace Tests\Cases\Feature;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class BR000016_TimesheetEntryStartsBeforeItEndsTest extends TestCase
{
    /** @test */
    public function correctIncorrectStartedAndEndedAtDateTimes()
    {
        Log::info(__METHOD__);

        $token = $this->login('user0001@test.com', 'Welcome123');

        $endedAt = Carbon::now()->subMinute();
        $startedAt = Carbon::now();
        $response = $this->post('/api/v1/timesheet_entries', [
            'started_at' => $startedAt->format('Y-m-d H:i:s'),
            'ended_at' => $endedAt->format('Y-m-d H:i:s'),
            'description' => 'BR000016-0001'
        ], [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('timesheet_entries', [
            'description' => 'BR000016-0001',
            'ended_at' => $startedAt,
            'started_at' => $endedAt
        ]);
    }
}
