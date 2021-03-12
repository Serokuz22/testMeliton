<?php
namespace App\Http\Middleware;

use Closure;

class CheckAutchToken
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
        $token = $request->bearerToken();
        if($token != env('AUTH_TOCKEN'))
            abort(403);

        return $next($request);
    }
}
