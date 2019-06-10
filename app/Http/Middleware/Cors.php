<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        $host=$request->getHost();
        $domains=[$host.':8080', $host.':80'];

        if (isset($request->server() ['HTTP_ORIGIN'])) {
            $origin=$request->server() ['HTTP_ORIGIN'];
            $pattern=preg_replace('#^https?://#', '', $origin);

            if (in_array($pattern, $domains)) {
                return $next($request)
                    ->header('Access-Control-Allow-Origin', $origin)
                    ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Authorization')
                    ->header('Access-Control-Allow-Methods', 'PUT, GET, POST, DELETE, OPTIONS');
            }
        }
        return $next($request);
    }
}
