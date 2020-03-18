<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Collection;

class CollectionResponseEtags
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
        $response = $next($request);
        $originalContent = $response->getOriginalContent();
        if(
            $request->isMethod('get') &&
            is_object($originalContent) &&
            get_class($originalContent) == Collection::class
        ) {
            $eTag = '';
            foreach($originalContent as $model) {
                $eTag .= $model->id . ':' . $model->eTag() . ';';
            }
            $eTag = substr($eTag, 0, -1);
            $response->setEtag($eTag);
        }

        return $response;
    }
}
