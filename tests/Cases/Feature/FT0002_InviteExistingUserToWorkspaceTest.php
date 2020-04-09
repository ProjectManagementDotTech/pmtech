<?php

namespace Tests\Cases\Feature;

use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class FT0002_InviteExistingUserToWorkspaceTest extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userRepository = new UserRepository();
        $this->workspaceRepository = new WorkspaceRepository(
            $this->userRepository);
    }

    /** @test */
    public function inviteExistingUser()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $workspace = $this->workspaceRepository->first([
            'name' => 'UT0004-0001'
        ]);
        $this->assertEquals(2, count($workspace->users));

        $response = $this->post('/api/v1/workspaces/' . $workspace->id .
            '/invite', [
                'email' => 'user0004@test.com'
            ]
        );
        $response->assertNoContent(201);

        $workspace->refresh();
        $this->assertEquals(3, $workspace->users()->count());
        $this->assertEquals(1, $workspace
            ->users()
            ->where('email', 'user0001@test.com')
            ->count()
        );
        $this->assertEquals(1, $workspace
            ->users()
            ->where('email', 'user0002@test.com')
            ->count()
        );
        $this->assertEquals(1, $workspace
            ->users()
            ->where('email', 'user0004@test.com')
            ->count()
        );
    }

    protected $userRepository;
    protected $workspaceRepository;
}
