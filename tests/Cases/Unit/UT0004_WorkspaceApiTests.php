<?php

namespace Tests\Cases\Unit;

use Tests\Shared\TestCase;

class UT0004_WorkspaceApiTests extends TestCase
{
    /** @test */
    public function listWorkspacesBeforeLogin()
    {
        $response = $this->get('/api/v1/workspaces');
        $response->assertStatus(401)->assertJson([
            'message' => 'Unauthenticated.'
        ]);
    }

    /** @test */
    public function listWorkspacesAfterLogin()
    {
        $token = $this->login('user0001@test.com', 'Welcome123');
        $response = $this->get('/api/v1/workspaces', [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(200)->assertJsonCount(1)->assertJsonFragment([
            'name' => 'Test0001'
        ]);
    }
}
