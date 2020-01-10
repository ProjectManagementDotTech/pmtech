<?php

namespace App\Repositories;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\Uuid;

class UserRepository
{
    //region Static Public Status Report

    /**
     * Create a new User based on the given data.
     *
     * @param array $data
     * @return User
     * @throws \Exception
     */
    static public function create(array $data): User
    {
        $data['id'] = Uuid::uuid4()->toString();
        $user = User::create($data);
        SettingsRepository::create($user);

        return $user;
    }

    /**
     * The user matching the given $email address.
     *
     * @param string $email
     * @return User
     */
    static public function byEmail(string $email): User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Return all users who are verified.
     *
     * @return array
     */
    static public function verifiedUsers(): Collection
    {
        return User::query()
            ->whereNotNull('email_verified_at')
            ->get();
    }

    //endregion
}
