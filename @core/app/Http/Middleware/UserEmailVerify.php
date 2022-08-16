<?php

namespace App\Http\Middleware;

use Closure;

class UserEmailVerify
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
        if (auth()->check() && auth()->user()->email_verified == 0 && empty(get_static_option('disable_user_email_verify'))){
            return redirect()->route('user.email.verify');
        }
        return $next($request);
    }
}
