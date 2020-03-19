<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserRepository as UserRepositoryInterface;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository implements UserRepositoryInterface
{
    //region Public Status Report

    /**
     * @inheritDoc
     */
    public function create(array $attributes = []): Model
    {
        return User::create($attributes);
    }

    /**
     * @inheritDoc
     */
    public function find($id): ?Model
    {
        return User::find($id);
    }

    /**
     * @inheritDoc
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * @inheritDoc
     */
    public function verified(): Collection
    {
        return User::whereNotNull('email_verified_at')->get();
    }

    //endregion
}
