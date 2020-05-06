<?php

namespace Tests\Cases\BusinessRequirements;

use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class BR000017_TimesheetEntriesCannotOverlapTest extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->workspaceRepository =
            new WorkspaceRepository(new UserRepository);
    }

    /** @test */
    public function timesheetEntriesCannotOverlap()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $workspace = $this->workspaceRepository->findFirstByName('Test0001');
        $response = $this->post('/api/v1/timesheet_entries', [
            'workspace_id' => $workspace->id,
            'description' => 'BR000017-0001',
            'started_at' => Carbon::now()->subSeconds(10)
        ]);
        $response->assertStatus(422);
    }

    protected $workspaceRepository;
}
