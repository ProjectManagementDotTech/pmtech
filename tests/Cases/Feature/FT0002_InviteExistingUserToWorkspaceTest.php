<?php

namespace Tests\Cases\Feature;

use App\Repositories\WorkspaceRepository;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class FT0002_InviteExistingUserToWorkspaceTest extends TestCase
{
    /** @test */
    public function inviteExistingUser()
    {
        Log::info(__METHOD__);

        $this->login('user0001@test.com', 'Welcome123');
        $workspace = WorkspaceRepository::filter(['name' => 'UT0004-0001'])[0];
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
}
