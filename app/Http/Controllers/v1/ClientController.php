<?php

namespace App\Http\Controllers\v1;

use App\Client;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ClientRepositoryInterface;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    //region Construction

    /**
     * ClientController constructor.
     *
     * @param ClientRepositoryInterface $clientRepository
     */
    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    //endregion

    //region Public Status Report

    public function show(Client $client)
    {
        return $client;
    }

    //endregion

    //region Protected Attributes

    protected $clientRepository;

    //endregion
}
