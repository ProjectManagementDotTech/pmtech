<?php

use App\User;
use App\Repositories\UserRepository;
use App\Repositories\WorkspaceRepository;

use Illuminate\Database\Seeder;

class TE_WorkspacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRepository = new UserRepository();
        $workspaceRepository = new WorkspaceRepository($userRepository);
        $user = User::where('email', 'user0001@test.com')->first();
        $workspaceRepository->create([
            'owner_user_id' => $user->id,
            'name' => 'Test0001'
        ]);
    }
}
