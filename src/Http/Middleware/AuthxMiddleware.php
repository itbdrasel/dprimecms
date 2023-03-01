<?php

namespace Sourcebit\Dprimecms\Http\Middleware;

use Closure;
class AuthxMiddleware
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
		//Sentinel::logout();
		//dd(Sentinel::getUser()->hotel_id);
		
        $auth = \App::make('Sourcebit\Dprimecms\Repositories\AuthInterface');

		if ($auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('users/login');
            }
        }
        return $next($request);
    }
}
