<?php

namespace App\Http\Middleware;

use App\Member;
use Closure;

class Memberidentify
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
        $token=$request->bearerToken();
        if ($token==null) {
            return response(['error'=>'without token'], 401);
        }
        if (!Member::where('api_token', $token)->exists()) {
            return response(['error'=>'token does not exists'], 401);
        }
        return $next($request);
    }
}
