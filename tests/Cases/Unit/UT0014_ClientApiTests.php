<?php

namespace Tests\Cases\Unit;

use App\Client;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class UT0014_ClientApiTests extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userRepository = new UserRepository();
    }

    /** @test */
    public function createClient()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $user = $this->userRepository->findFirstByEmail('user0001@test.com');
        $workspace = $user->ownedWorkspaces()->where('name', 'UT0004-0001')
            ->first();

        $response = $this->post('/api/v1/workspaces/' . $workspace->id . '/clients', [
            'name' => 'UT0014-0001 Ltd'
        ]);
        $response->assertStatus(201)->assertHeader('Location');
        $this->assertDatabaseHas('clients', [
            'workspace_id' => $workspace->id,
            'name' => 'UT0014-0001 Ltd'
        ]);
    }

    /** @test */
    public function createClientAgain()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $user = $this->userRepository->findFirstByEmail('user0001@test.com');
        $workspace = $user->ownedWorkspaces()->where('name', 'UT0004-0001')
            ->first();

        $response = $this->post('/api/v1/workspaces/' . $workspace->id . '/clients', [
            'name' => 'UT0014-0001 Ltd'
        ]);
        $response->assertStatus(422)->assertJson([
            'message' => 'The given data was invalid.',
            'errors' => [
                'name' => [
                    'The name is already used.'
                ]
            ]
        ]);
    }

    /** @test */
    public function createClientAgainInDifferentWorkspace()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $user = $this->userRepository->findFirstByEmail('user0001@test.com');
        $workspace = $user->ownedWorkspaces()->where('name', 'Test0001')
            ->first();

        $response = $this->post('/api/v1/workspaces/' . $workspace->id . '/clients', [
            'name' => 'UT0014-0001 Ltd'
        ]);
        $response->assertStatus(201)->assertHeader('Location');
        $this->assertDatabaseHas('clients', [
            'workspace_id' => $workspace->id,
            'name' => 'UT0014-0001 Ltd'
        ]);
        $clients = Client::where('name', 'UT0014-0001 Ltd')->get();
        $this->assertCount(2, $clients);
    }

    /** @test */
    public function indexClients()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $user = $this->userRepository->findFirstByEmail('user0001@test.com');
        $workspace = $user->ownedWorkspaces()->where('name', 'UT0004-0001')
            ->first();

        $response = $this->get('/api/v1/workspaces/' . $workspace->id .
            '/clients');
        $response->assertStatus(200)->assertSee('UT0014-0001 Ltd');
        $this->assertDatabaseHas('clients', [
            'workspace_id' => $workspace->id,
            'name' => 'UT0014-0001 Ltd'
        ]);

    }

    protected $userRepository;
}
