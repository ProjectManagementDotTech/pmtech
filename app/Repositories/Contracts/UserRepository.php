<?php

namespace App\Repositories\Contracts;

use App\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepository extends Repository
{
    /**
     * Find the User model that has $emailAddress.
     *
     * @param string $emailAddress
     * @return User|null
     */
    public function findByEmail(string $emailAddress): ?User;

    /**
     * All users that have their email verified.
     *
     * @return Collection
     */
    public function verified(): Collection;
}
