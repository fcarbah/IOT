<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes;

/**
 * Description of Application
 *
 * @author FMCJr
 */
class Application {
    
    use \App\Traits\ResponseTrait,\App\Traits\AppLog;
    
    public function login($username,$password){
        return Authentication::loginWithCredentials($username, $password);
    }
    
    public function logout(){
        //To Do execute some actions
        $this->log(auth()->user()->id, \EventType::Logout, "User Logout", auth()->user()->username);
        $this->log(auth()->user()->id, \EventType::UserOffline, "User Offline", auth()->user()->username);
        session()->flush();
        return $this->buildResponse('User Logout Successfully', 'success', FALSE);
    }
    
    public static function getUser(){
        
        $user = auth()->user();
        
        return $user != null? new UserAccount($user) : null;
    }

}
