<?php

namespace Tests\Setup;

use App\Workspace;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\Shared\TestCase;

class Setup extends TestCase
{
    public function testInitializeRegressionTest()
    {
        Mail::fake();

        Artisan::call('migrate:fresh');
        Artisan::call('db:seed', ['--class' => 'TestEnvironmentSeeder']);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User 0001'
        ]);

        $this->assertDatabaseHas('workspaces', [
            'name' => 'Test0001'
        ]);

        $workspace = Workspace::where('name', 'Test0001')->first();
        $this->assertNotNull($workspace->ownerUser);
    }
}

