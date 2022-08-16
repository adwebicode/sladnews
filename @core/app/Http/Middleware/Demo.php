<?php

namespace App\Http\Middleware;

use Closure;

class Demo
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
        if($request->isMethod('POST') || $request->isMethod('PUT')) {
            if ($request->ajax()){
                return response()->json(['type' => 'warning' , 'msg' => 'This is Demo version. You can not change anything.']);
            }
            return redirect()->back()->with(['type' => 'warning' , 'msg' => 'This is Demo version. You can not change anything.']);
        }
        return $next($request);
    }
}
