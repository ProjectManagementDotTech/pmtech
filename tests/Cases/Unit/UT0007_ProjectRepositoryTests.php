<?php

namespace Tests\Cases\Unit;

use App\Repositories\ProjectRepository;
use App\Workspace;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class UT0007_ProjectRepositoryTests extends TestCase
{
    /** @test */
    public function createProject()
    {
        Log::info(__METHOD__);

        $workspace = Workspace::where('name', 'Test0001')->first();
        ProjectRepository::create([
            'workspace_id' => $workspace->id,
            'name' => 'UT0007-0001'
        ]);

        $this->assertDatabaseHas('projects', [
            'workspace_id' => $workspace->id,
            'name' => 'UT0007-0001'
        ]);
    }
}
