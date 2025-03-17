<?php

namespace App\Http\Middleware;
use JWTAuth;
use App\Models\Tourist;

use Closure;

class VerifyClaim
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
        $lastLogin = JWTAuth::payload($request->token)['ll'];
        $user = JWTAuth::toUser($request->token);

        if ($user->last_login == $lastLogin) {
            return $next($request);
        }else{
            return response()->json(['status' => 401 ,'message' => 'Invalid Token']);
        }
    }
}
