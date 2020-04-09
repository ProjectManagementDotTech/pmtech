<?php

namespace Tests\Cases\BusinessRequirements;

use App\Repositories\WorkspaceRepository;
use Tests\Shared\TestCase;

class BR000022_ProjectNameMustBeUniqueWithinAWorkspace extends TestCase
{
    /** @test */
    public function createNewProjectWithSameNameInWorkspace()
    {
        $this->login('user0001@test.com', 'Welcome123');
        $workspace = WorkspaceRepository::filter([
            'name' => 'Test0001'
        ])[0];

        $response = $this->post('/api/v1/workspaces/' . $workspace->id . '/projects', [
            'color' => 'E19C41',
            'name' => 'UT0008-0001'
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
}
