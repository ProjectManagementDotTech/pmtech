<?php

namespace App\Http\Middleware;

use Closure;

class VerifyEtag
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ifMatchEtag = $request->headers->get('If-Match');
        if($ifMatchEtag) {
            $ifMatchEtag = str_replace('"', '', $ifMatchEtag);

            $modelEtag = $request
                ->route()
                ->parameter($request->route()->parameterNames[0])
                ->eTag();

            if($modelEtag != $ifMatchEtag) {
                return response('', 412);
            } else {
                return $next($request);
            }
        } else {
            return response('', 412);
        }
    }
}
