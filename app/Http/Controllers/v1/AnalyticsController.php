<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\StoreAnalyticsRequest;
use App\Repositories\Contracts\AnalyticsRepository as AnalyticsRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    //region Public Construction

    public function __construct(
        AnalyticsRepositoryInterface $analyticsRepository)
    {
        $this->analyticsRepository = $analyticsRepository;
    }

    //endregion

    //region Public Status Report

    public function create(StoreAnalyticsRequest $request)
    {
        $this->analyticsRepository->create($request->validated());

        return response('', 201);
    }

    //endregion

    //region Protected Attributes

    protected $analyticsRepository;

    //endregion
}
