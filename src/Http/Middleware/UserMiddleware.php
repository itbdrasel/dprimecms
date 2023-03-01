<?php

namespace Sourcebit\Dprimecms\Http\Middleware;
use Illuminate\Support\Facades\Route;
use Closure;

class UserMiddleware
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
        $auth = \App::make('Sourcebit\Dprimecms\Repositories\AuthInterface');

		if($auth->check()){

                if( Route::currentRouteName() ){

                    if($auth->hasAccess(Route::currentRouteName())){
                        return $next($request);
                    }else{
                        die('No permission.');
                        //return redirect('users/profile');
                    }

                }else{
                    die('No route name found for this request.');
                }
        }else{
            return redirect('/');
        }


    }
}
