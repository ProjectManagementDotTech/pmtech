<?php

use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TE_UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userRepository = new UserRepository();
        $userRepository->create([
            'name' => 'Test User 0001',
            'email' => 'user0001@test.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('Welcome123')
        ]);
    }
}
