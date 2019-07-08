<?php

namespace App\Http\Middleware;

use App\Boss;
use Closure;

class Bossidentify
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
        if (!Boss::where('api_token', $token)->exists()) {
            return response(['error'=>'token does not exist'], 401);
        }
        return $next($request);
    }
}
