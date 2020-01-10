<?php

namespace Tests\Cases\Unit;

use App\Project;
use Illuminate\Support\Facades\Log;
use Tests\Shared\TestCase;

class UT0010_TaskApiTests extends TestCase
{
    /** @test */
    public function createTaskInOwnedProject()
    {
        Log::info(__METHOD__);

        $project = Project::where('name', 'PR000001 - Sample Project')->first();
        $token = $this->login('user0001@test.com', 'Welcome123');
        $response = $this->post('/api/v1/projects/' . $project->id . '/tasks', [
            'name' => 'T000001 - Sample Task'
        ], [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(201)->assertHeader('Location');
    }

    /** @test */
    public function createTaskInNotOwnedProject()
    {
        Log::info(__METHOD__);
    }

    /** @test */
    public function listProjectTasks()
    {
        Log::info(__METHOD__);

        $project = Project::where('name', 'PR000001 - Sample Project')->first();
        $token = $this->login('user0001@test.com', 'Welcome123');
        $response = $this->get('/api/v1/projects/' . $project->id . '/tasks', [
            'Authorization' => $token['type'] . ' ' . $token['token']
        ]);
        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'T000001 - Sample Task'
            ])
            ->assertJsonCount(1);
    }
}
