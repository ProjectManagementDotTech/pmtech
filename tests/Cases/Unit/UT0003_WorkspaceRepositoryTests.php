<?php

namespace Tests\Cases\Unit;

use App\User;
use App\Workspace;
use App\Repositories\WorkspaceRepository;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class UT0003_WorkspaceRepositoryTests extends TestCase
{
    /** @test */
    public function createWorkspace()
    {
        Log::info(__METHOD__);

        $user = User::where('email', 'user0001@test.com')->first();
        WorkspaceRepository::create([
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

        $workspace = Workspace::where('name', 'UT0003-0001')->first();
        WorkspaceRepository::update($workspace, [
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

        $temp = Workspace::where('name', 'UT0003-0002')->first();

        $workspace = WorkspaceRepository::get($temp->id);

        $this->assertNotNull($workspace);
        $this->assertEquals($temp->id, $workspace->id);
        $this->assertEquals($temp->owner_user_id, $workspace->owner_user_id);
        $this->assertEquals($temp->name, $workspace->name);
        $this->assertEquals($temp->created_at, $workspace->created_at);
        $this->assertEquals($temp->updated_at, $workspace->updated_at);
        $this->assertEquals($temp->deleted_at, $workspace->deleted_at);
    }

    /** @test */
    public function archiveWorkspace()
    {
        Log::info(__METHOD__);

        $workspace = Workspace::where('name', 'UT0003-0002')->first();

        WorkspaceRepository::archive($workspace);

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

        WorkspaceRepository::restore($workspace);

        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id
        ]);
        $workspace = Workspace::find($workspace->id);
        $this->assertEquals(NULL, $workspace->deleted_at);
        $this->assertEquals('UT0003-0002', $workspace->name);
    }

    /** @test */
    public function restoreWorkspaceById()
    {
        Log::info(__METHOD__);

        $user = User::where('email', 'user0001@test.com')->first();
        $workspace = WorkspaceRepository::create([
            'owner_user_id' => $user->id,
            'name' => 'UT0003-0001'
        ]);

        WorkspaceRepository::archive($workspace);

        $workspace = Workspace::withTrashed()
            ->where('name', 'UT0003-0001')
            ->first();
        $this->assertNotNull($workspace->deleted_at);

        $restoredWorkspace = WorkspaceRepository::restoreById($workspace->id);
        $this->assertNull($restoredWorkspace->deleted_at);

        $this->assertDatabaseHas('workspaces', [
            'id' => $workspace->id,
            'deleted_at' => NULL
        ]);
    }

    /** @test */
    public function deleteWorkspace()
    {
        Log::info(__METHOD__);

        $user = User::where('email', 'user0001@test.com')->first();
        foreach($user->ownedWorkspaces as $workspace) {
            if($workspace->name != 'Test0001') {
                WorkspaceRepository::delete($workspace);
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
}
