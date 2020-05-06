<?php

namespace App\Repositories\Contracts;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface extends RepositoryInterface
{
    //region Public Status Report

    /**
     * Find all User models that matching $email, and, by default, paginate the
     * result.
     *
     * @param string $email
     * @param bool $paginate
     * @return Collection|LengthAwarePaginator
     */
    public function findAllByEmail(string $email, bool $paginate = TRUE);

    /**
     * Find the first User model that has $email.
     *
     * @param string $email
     * @return User|null
     */
    public function findFirstByEmail(string $email): ?User;

    /**
     * All users that have their email verified.
     *
     * @return Collection
     */
    public function verified(): Collection;

    //endregion
}
