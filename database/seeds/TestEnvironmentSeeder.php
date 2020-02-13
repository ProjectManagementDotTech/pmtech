<?php

use Illuminate\Database\Seeder;

class TestEnvironmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TE_UsersTableSeeder::class);
        $this->call(TE_WorkspacesTableSeeder::class);
    }
}
