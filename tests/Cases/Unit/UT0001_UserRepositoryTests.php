<?php

namespace Tests\Cases\Unit;

use App\Repositories\UserRepository;
use Illuminate\Support\Str;
use Tests\Shared\TestCase;

class UT0001_UserRepositoryTests extends TestCase
{
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->userRepository = new UserRepository();
    }

    /** @test */
    public function createUser()
    {
        $name = 'UT0001-0001';
        $email = 'UT0001-0001@test.com';
        $password = Str::random(10);

        $this->userRepository->create([
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

    /** @test */
    public function findUserByEmail()
    {
        $user = $this->userRepository->findByEmail('UT0001-0001@test.com');
        $this->assertNotNull($user);
        $this->assertEquals('UT0001-0001', $user->name);
    }

    /** @test */
    public function cannotFindUserByEmail()
    {
        $user = $this->userRepository->findByEmail('some@email.com');
        $this->assertNull($user);
    }

    public function thereAreNoUsersWithVerifiedEmailAddresses()
    {
        $users = $this->userRepository->verified();
        $this->assertEmpty($users);
    }

    protected $userRepository;
}
