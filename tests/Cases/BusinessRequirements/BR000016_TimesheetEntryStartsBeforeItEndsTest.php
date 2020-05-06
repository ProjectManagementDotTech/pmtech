<?php

namespace Tests\Cases\BusinessRequirements;

use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use App\Workspace;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class BR000016_TimesheetEntryStartsBeforeItEndsTest extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->workspaceRepository =
            new WorkspaceRepository(new UserRepository);
    }

    /** @test */
    public function correctIncorrectStartedAndEndedAtDateTimes()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $workspace = $this->workspaceRepository->findFirstByName('Test0001');
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

    protected $workspaceRepository;
}
