<?php

namespace Tests\Cases\BusinessRequirements;

use App\Repositories\WorkspaceRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class BR000016_TimesheetEntryStartsBeforeItEndsTest extends TestCase
{
    /** @test */
    public function correctIncorrectStartedAndEndedAtDateTimes()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $workspace = WorkspaceRepository::filter([
            'name' => 'Test0001'
        ])[0];
        $endedAt = Carbon::now()->subMinute();
        $startedAt = Carbon::now();
        $response = $this->post('/api/v1/timesheet_entries', [
            'workspace_id' => $workspace->id,
            'description' => 'BR000016-0001',
            'ended_at' => $endedAt->format('Y-m-d H:i:s'),
            'started_at' => $startedAt->format('Y-m-d H:i:s')
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('timesheet_entries', [
            'workspace_id' => $workspace->id,
            'description' => 'BR000016-0001',
            'ended_at' => $startedAt,
            'started_at' => $endedAt
        ]);
    }
}
