<?php

namespace App\Repositories;

use App\Analytics;
use App\Repositories\Contracts\AnalyticsRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class AnalyticsRepository implements AnalyticsRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(array $attributes = []): Model
    {
        return Analytics::create($attributes);
    }
}
