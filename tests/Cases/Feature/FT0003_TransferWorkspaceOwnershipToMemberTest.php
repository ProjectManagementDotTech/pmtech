<?php

namespace Tests\Cases\Feature;

use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class FT0003_TransferWorkspaceOwnershipToMemberTest extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userRepository = new UserRepository();
    }

    /** @test */
    public function transferOwnershipToMember()
    {
        Log::info(__METHOD__);

        $user = $this->userRepository->findByEmail('user0001@test.com');
        $workspace = $user->ownedWorkspaces()->where('name', 'UT0004-0001')
            ->first();
        $newOwner = $this->userRepository->findByEmail('user0004@test.com');
        $this->login('user0001@test.com', 'Welcome123');

        $response = $this->post('/api/v1/workspaces/' . $workspace->id .
            '/transfer/' . $newOwner->id);
        $response->assertNoContent(201);
        $workspaceReloaded = WorkspaceRepository::find($workspace->id);
        $this->assertNotEquals($workspace->owner_user_id,
            $workspaceReloaded->owner_user_id);
        $this->assertEquals($newOwner->id, $workspaceReloaded->owner_user_id);

        /*
         * Put it back, because other tests depend on this...
         */
        $workspaceReloaded->owner_user_id = $user->id;
        $workspaceReloaded->save();
    }

    /** @test */
    public function transferOwnershipToNonMember()
    {
        Log::info(__METHOD__);

        $user = $this->userRepository->findByEmail('user0001@test.com');
        $workspace = $user->ownedWorkspaces()->where('name', 'UT0004-0001')
            ->first();
        $newOwner = $this->userRepository->findByEmail('user0005@test.com');
        $this->login('user0001@test.com', 'Welcome123');

        $response = $this->post('/api/v1/workspaces/' . $workspace->id .
            '/transfer/' . $newOwner->id);
        $response->assertStatus(403);
        $workspaceReloaded = WorkspaceRepository::find($workspace->id);
        $this->assertEquals($workspace->owner_user_id,
            $workspaceReloaded->owner_user_id);
        $this->assertNotEquals($newOwner->id, $workspaceReloaded->owner_user_id);
    }

    protected $userRepository;
}
