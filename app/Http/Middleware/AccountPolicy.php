<?php

namespace App\Http\Middleware;

use Closure;

class AccountPolicy
{
    use \App\Traits\StorageTrait,\App\Traits\ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //\Cache::flush();
        $user = \App\Classes\Authentication::findUser($request->get('username'));
        $key = \AccountPolicyCodes::ThresholdKeyAbbr.$user->username;
        $key2 = \AccountPolicyCodes::LockoutKeyAbbr.$user->username;
        
        
        if($this->retrieveFromStorage($key2) != null){
            if($request->ajax() || $request->wantsJson()){     
                return response()->json($this->failResponse('Account Locked Too many Failed Login Attempts!','<h3>Authentication Error</h3>'));
            }
            else{
                abort(403,'403 !Unauthorized'); 
            }
        }

        if($this->retrieveFromStorage($key) != null){
            if($request->ajax() || $request->wantsJson()){
                return response()->json($this->failResponse('Maximum amount of Failed Login Attempts reached!','<h3>Authentication Error</h3>'));
            }
            else{
                abort(403,'403 !Unauthorized'); 
            }
        }
        
        return $next($request);
    }
}
