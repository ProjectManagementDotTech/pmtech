<?php

namespace Tests\Cases\BusinessRequirements;

use Tests\Shared\TestCase;

class BR000013_WorkspaceNamesMustBeUniqueForTheOwnerUserTest extends TestCase
{
    /** @test */
    public function createAnotherUT0004Dash0001Workspace()
    {
        $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/workspaces', [
            'name' => 'UT0004-0001'
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
    public function createUT0004Dash0001WorkspaceForADifferentUser()
    {
        $this->login('user0004@test.com', 'Welcome123');
        $response = $this->post('/api/v1/workspaces', [
            'name' => 'UT0004-0001'
        ]);
        $response->assertStatus(201)->assertHeader('Location');
    }
}
