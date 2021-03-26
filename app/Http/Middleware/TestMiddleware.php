<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class TestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(env('APP_DEBUG')){
            if(\Cache::has('token')){
                $request->headers->set('Authorization', 'Bearer ' .\Cache::get('token'));
            }else{
                $user = User::oldest('id')->first();
                $token = $user->createToken('mobile')->plainTextToken;
                \Cache::set('token', $token, now()->addDays(100));
            }
        }
       
        return $next($request);
    }
}
