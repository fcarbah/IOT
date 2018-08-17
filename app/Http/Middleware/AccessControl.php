<?php

namespace App\Http\Middleware;

use Closure;
use App\Classes\Repos\SecurityRepo;

class AccessControl
{
    use \App\Traits\ResponseTrait;
    
    private $settings;
    private $rules;
    private $requestIp;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->initialize();
        if($this->settings->config->enableIpFilter && !$this->hasAccess()){
            
            if($request->ajax() || $request->wantsJson()){
                $res = $this->failResponse('Redirecting to Login Page','<h3>403 - Forbidden!!</h3>');
                $res->title = '';
                $res->redirect = true;
                $res->redirectState = 'forbidden';
                return response()->json($res,400);
            }
            
            abort(403,'403 !Unauthorized');
        }
        
        return $next($request);
    }
    
    protected function initialize(){
        $this->settings = SecurityRepo::getInstance()->getFilters()->data;
        $this->rules = $this->settings->filters;
        $this->requestIp = /*'54.51.2.255';//*/\Illuminate\Support\Facades\Request::ip();
    }
    
    protected function hasAccess(){
        
        $denyRules = $this->rules->filter(function($item){
            
            if(strtolower($item->accessType) =='deny'){
                return $item;
            }
        })->all();
        
        $allowRules = $this->rules->filter(function($item){
            
            if(strtolower($item->accessType) =='allow'){
                return $item;
            }
        })->all();
        
        if($this->isInRules($denyRules) && !$this->isInRules($allowRules)){
            return false;
        }
        
        return true;
        
    }
    
    protected function isInRules($rules){

        foreach($rules as $rule){
            
            if(ipInRange($this->requestIp, $rule->startIp, $rule->endIp)){
                return true;
            }
            
            /*if(isIpInRange($this->requestIp, $rule->startIp, $rule->compareOctet) && 
                isIpWithinRange($this->requestIp, $rule->startIp, $rule->endIp, $rule->compareOctet)){
                return true;
            }*/
            
        }
        
        return false;
        
    }
}
