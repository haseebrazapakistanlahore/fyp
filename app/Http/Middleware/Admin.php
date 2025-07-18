<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use View;
use App\Models\UserPermission;
class Admin
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

          if (Auth::user() != null && Auth::user()->is_active == 1) {
            
            $response = $next($request);
            return $response->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
                ->header('Pragma','no-cache')
                ->header('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
        }
        Auth::guard()->logout();
        $request->session()->invalidate();
        return redirect('/')->with('error', 'You don\'t have access OR Your account is suspended');

    }
}
