<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Http\Requests\IpFilterRequest;
use App\Http\Requests\AccountPolicyRequest;
/**
 * Description of SecurityController
 *
 * @author FMCJr
 */
class SecurityController extends Controller {
   
    public function __construct() {
        parent::__construct();
    }
    
    public function addIpFilter(IpFilterRequest $request){
        $res = $this->loginUser->securityRepo()->addIpFilter($request->except(['_token']));
        return response()->json($res);
    }
    
    public function deleteIpFilter($id){
        $res = $this->loginUser->securityRepo()->deleteIpFilter($id);
        return response()->json($res); 
    }
    
    public function editPolicy($id, AccountPolicyRequest $request){
        $res = $this->loginUser->securityRepo()->updateAccountPolicy($id,$request->except(['_token','id','role']));
        return response()->json($res); 
    }
    
    public function editIpFilter($id,IpFilterRequest $request){
        $res = $this->loginUser->securityRepo()->editIpFilter($id,$request->except(['_token','id']));
        return response()->json($res); 
    }
    
    public function getPolicies(){
        $res = $this->loginUser->securityRepo()->getAccountPolicies();
        return response()->json($res);
    }
    
    public function getIpFilters(){
        $res = $this->loginUser->securityRepo()->getFilters();
        return response()->json($res);
    }
    
}
