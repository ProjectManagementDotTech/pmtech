<?php

namespace App\Repositories;

use App\Repositories\Concerns\ConstructsRepository;
use App\Repositories\Concerns\CreatesModel;
use App\Repositories\Concerns\DeletesModel;
use App\Repositories\Concerns\FindsModel;
use App\Repositories\Concerns\UpdatesModel;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    use ConstructsRepository, CreatesModel, DeletesModel, FindsModel;
    use UpdatesModel;

    //region Public Construction

    public function __construct()
    {
        $this->modelClass = User::class;
        $this->usesSoftDeletes = TRUE;
    }

    //endregion

    //region Public Status Report

    /**
     * @inheritDoc
     */
    public function findAllByEmail(string $email, bool $paginate = TRUE)
    {
        if($paginate) {
            return User::where('email', $email)->paginate();
        } else {
            return User::where('email', $email)->get();
        }
    }

    /**
     * @inheritDoc
     */
    public function findFirstByEmail(string $email): ?User
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
