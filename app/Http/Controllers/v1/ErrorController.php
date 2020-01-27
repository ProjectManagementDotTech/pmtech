<?php

namespace App\Http\Controllers\v1;

use App\Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ErrorController
{
    public function store(Request $request)
    {
        /*
         * Potentially filter out non-errors (things that occur but are not
         * really errors, really)
         */

        Log::error($request->input('error', ''));

        /*
         * If the error is deemed critical, use the Log facility to report it as
         * well.
         */

        return response('', 201);
    }
}
