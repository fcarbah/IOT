<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
/**
 * Description of SessionController
 *
 * @author FMCJr
 */
class SessionController extends Controller{
    
    public function __construct() {
        parent::__construct();
    }
    
    public function checkAuthentication(){
        
        if(auth()->check()){
            $info = \App\Models\DeviceInfo::first();
            $authData = $this->loginUser->me();
            $authData->setupComplete = $info != null ? $info->setup_complete : false;
            $res = \App\Classes\AppResponse::make('', 'success', FALSE, $authData);
        }else{
            $res = \App\Classes\AppResponse::make('', 'danger', true);
        }
        
        return response()->json($res);
    }
    
    public function login(LoginRequest $request){
        $res = (new \App\Classes\Application())->login($request->get('username'), $request->get('password'));
        return response()->json($res);
    }
    
    public function logout(){
        $res = (new \App\Classes\Application())->logout();
        return response()->json($res);
    }
    
}
