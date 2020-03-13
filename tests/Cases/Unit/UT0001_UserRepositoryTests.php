<?php

namespace Tests\Cases\Unit;

use App\Repositories\UserRepository;
use Illuminate\Support\Str;
use Tests\Shared\TestCase;

class UT0001_UserRepositoryTests extends TestCase
{
    /** @test */
    public function createUser()
    {
        $name = Str::random(8);
        $email = Str::random(16);
        $password = Str::random(10);

        UserRepository::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
    }
}
