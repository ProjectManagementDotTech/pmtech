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

        $settingsRepository = new SettingsRepository();
        $settingsRepository->create(['user_id' => $user->id]);

        return $user;
    }

    /**
     * The user matching the given $email address.
     *
     * @param string $email
     * @return User|null
     */
    static public function byEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Get the user identified by $id.
     *
     * @param string $id
     * @return User|null
     */
    static public function find(string $id): ?User
    {
        return User::find($id);
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
