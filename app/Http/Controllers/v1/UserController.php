<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //region Public Status Report

    /**
     * The logged-in user
     *
     * @return mixed
     */
    public function self()
    {
        return Auth::user();
    }

    //endregion
}
