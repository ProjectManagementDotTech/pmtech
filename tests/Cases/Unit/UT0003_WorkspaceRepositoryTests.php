<?php

namespace Tests\Cases\Unit;

use App\Repositories\UserRepository;
use App\User;
use App\Workspace;
use App\Repositories\WorkspaceRepository;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class UT0003_WorkspaceRepositoryTests extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->userRepository = new UserRepository();
        $this->workspaceRepository = new WorkspaceRepository(
            $this->userRepository);
    }

    /** @test */
    public function createWorkspace()
    {
        Log::info(__METHOD__);

        $user = User::where('email', 'user0001@test.com')->first();
        $this->workspaceRepository->create([
            'owner_user_id' => $user->id,
            'name' => 'UT0003-0001'
        ]);

        $this->assertDatabaseHas('workspaces', [
            'owner_user_id' => $user->id,
            'name' => 'UT0003-0001'
        ]);
    }

    /** @test */
    public function updateWorkspace()
    {
        Log::info(__METHOD__);

        $workspace = $this->workspaceRepository->findFirstByName('UT0003-0001');
        $this->workspaceRepository->update($workspace, [
            'name' => 'UT0003-0002'
        ]);

        $this->assertDatabaseMissing('workspaces', [
            'name' => 'UT0003-0001'
        ]);
        $this->assertDatabaseHas('workspaces', [
            'name' => 'UT0003-0002'
        ]);
    }

    /** @test */
    public function getWorkspace()
    {
        Log::info(__METHOD__);

        $workspace = $this->workspaceRepository->findFirstByName('UT0003-0002');

        $this->assertNotNull($workspace);
    }

    /** @test */
    public function archiveWorkspace()
    {
        Log::info(__METHOD__);

        $workspace = $this->workspaceRepository->findFirstByName('UT0003-0002');

        $this->workspaceRepository->archive($workspace);

        $this->assertSoftDeleted('workspaces', [
            'id' => $workspace->id
        ]);
        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id
        ]);
    }

    /** @test */
    public function restoreWorkspace()
    {
        Log::info(__METHOD__);

        $workspace = Workspace::withTrashed()
            ->where('name', 'UT0003-0002')
            ->first();

        $this->workspaceRepository->restore($workspace);

        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id
        ]);
        $workspace = Workspace::find($workspace->id);
        $this->assertEquals(NULL, $workspace->deleted_at);
        $this->assertEquals('UT0003-0002', $workspace->name);
    }

    /** @test */
    public function deleteWorkspace()
    {
        Log::info(__METHOD__);

        $user = User::where('email', 'user0001@test.com')->first();
        foreach($user->ownedWorkspaces as $workspace) {
            if($workspace->name != 'Test0001') {
                $this->workspaceRepository->delete($workspace);
                $this->assertDatabaseMissing('workspaces', [
                    'id' => $workspace->id,
                    'owner_user_id' => $user->id,
                    'name' => $workspace->name
                ]);
            }
        }

        $this->assertDatabaseHas('workspaces', [
            'owner_user_id' => $user->id,
            'name' => 'Test0001'
        ]);
    }

    protected $userRepository;

    protected $workspaceRepository;
}
