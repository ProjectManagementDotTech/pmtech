<?php

namespace Tests\Cases\BusinessRequirements;

use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class BR000017_TimesheetEntriesCannotOverlapTest extends TestCase
{
    /** @test */
    public function timesheetEntriesCannotOverlap()
    {
        Log::info(__METHOD__);

        $workspaceRepository = new WorkspaceRepository(new UserRepository);

        $this->login('user0001@test.com', 'Welcome123');
        $workspace = $workspaceRepository->first([
            'name' => 'Test0001'
        ]);
        $response = $this->post('/api/v1/timesheet_entries', [
            'workspace_id' => $workspace->id,
            'description' => 'BR000017-0001',
            'started_at' => Carbon::now()->subSeconds(10)
        ]);
        $response->assertStatus(422);
    }
}
