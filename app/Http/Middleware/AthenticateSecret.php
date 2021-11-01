<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpFoundation\Response;

class AthenticateSecret
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
        $validSecrets = explode(',', env('KEY_SECRET_GATEWAY'));
        if(in_array($request->header('Authorization'), $validSecrets))
        {
            return $next($request);
        }
        
        throw new UnauthorizedException();
        //abort(Response::HTTP_UNAUTHORIZED);
        
    }
}
