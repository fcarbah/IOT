<?php

namespace App\Http\Middleware;

use Closure;

class MobileApi
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
        $key = $request->get('key');
        
        $mobileKey = \App\Models\MobileKeys::where('key',$key)->first();
        
        if($mobileKey == null){
            
            if($request->ajax() || $request->wantsJson()){
                $response = \App\Classes\AppResponse::make('Invalid API key','danger',true, null, 'Authentication Failure',-100);
                return response()->json($response,400);
                
            }
            return redirect('/');
        }
        
        return $next($request);
    }
}
