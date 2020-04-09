<?php

namespace Tests\Cases\BusinessRequirements;

use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use App\User;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class BR000021_OnlyWorkspaceUsersCanRetrieveWorkspaceDetailsTest extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userRepository = new UserRepository();
        $this->workspaceRepository = new WorkspaceRepository(
            $this->userRepository);
    }

    /** @test */
    public function retrieveWorkspaceInformationWithAuthorizedUser()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');

        $workspace = $this->workspaceRepository->first([
            'name' => 'Test0001'
        ]);
        $response = $this->get('/api/v1/workspaces/' . $workspace->id);
        $response->assertStatus(200)->assertJsonFragment([
            'name' => 'Test0001'
        ]);
    }

    /** @test */
    public function retrieveWorkspaceInformationWithUnauthorizedUser()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');

        $user = $this->userRepository->findByEmail('user0004@test.com');
        $workspace = $user->ownedWorkspaces[0];
        $response = $this->get('/api/v1/workspaces/' . $workspace->id);
        $response->assertStatus(403);
    }

    protected $userRepository;
    protected $workspaceRepository;
}
