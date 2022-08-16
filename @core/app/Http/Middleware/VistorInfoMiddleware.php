<?php

namespace App\Http\Middleware;

use App\Helpers\getBrowser;
use App\Helpers\Mobile_Detect;
use App\VisitorInfo;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VistorInfoMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        $browser_info = ''; 
        
        try{
           $browser_info = getBrowser::name($request->userAgent());
        }catch(\Exception $e){
            
        }
        $detect = new Mobile_Detect();
        $device = 'Desktop/Laptop';
        if ($detect->isMobile()){
            $device = 'Mobile';
        }else if ($detect->isTablet()){
            $device = 'Tablet';
        }
        if (!(bool) Str::match('/admin-home/',$request->path())){
            VisitorInfo::updateOrcreate(
                [
                    'ip' => $request->ip(),
                    'visited_url' => $request->path(),
                    'os' => $browser_info['platform'] ?? '',
                    'device' => $device
                ],
                [
                    'reference' => optional($request->headers)->get('referer'),
                    'ip' =>  $request->ip(),
                    'os' => $browser_info['platform'] ?? '',
                    'browser' => $browser_info['name'] ?? '',
                    'visited_url' => $request->path(),
                    'country' => null,
                    'device' => $device
                ]
            );

        }

        return $next($request);
    }
}
