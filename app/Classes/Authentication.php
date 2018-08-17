<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes;

use \App\User;
use \App\Traits\AppLog;
use \App\Traits\ResponseTrait;
/**
 * Description of Authentication
 *
 * @author FMCJr
 */
class Authentication {
    
    use AppLog, ResponseTrait,\App\Traits\StorageTrait;
    
    private $username;
    
    public static function findUser($username){
        return User::where('username',$username)->orWhere('username','unknown')->with('role.accountPolicy')->first();
    }
    
    public function login($username,$password){
        
        $this->username = $username;
        
        $user = $this->findUser($username);
        
        if(!$user->status && $user->username != 'unknown'){
            $this->log($user->id, \EventType::LoginFail, "Account Suspended", $username);
            return $this->buildResponse("User Account Suspended", 'danger', true);
        }
        
        $res = $this->verifyUserStatus($user);

        \Auth::attempt(['username'=>$username,'password'=>$password,'canLogin'=>true]);
        
        if($res->error && !auth()->check() && ! $user->accountLocked){
            $this->log($user->id, \EventType::LoginFail, $res->messageStr(), $username);
            return $res;
        }
        else{
            
            if(auth()->check()){
                $this->resetUserAccountPolicyRestrictions($user);
                $this->log($user->id, \EventType::LoginSucces, "User logged in successfully", $username);
                $this->log($user->id, \EventType::UserOnline, "User logged in successfully", $username);
                return $this->successResponse("User Logged In Successfully",null,'<h3>Authentication Success</h3>');
            }

            $res = $this->handleFailedLoginAttempt($user);
            $this->log($user->id, \EventType::LoginFail, $res->messageStr(), $username);

            return $res;
        }

    }
    
    public static function loginAsMobile(){
        $user = User::where('username','Mobile')->first();
        \Auth::login($user,true);
    }
    
    public static function loginAsSystem(){
        $user = User::where('username','System')->first();
        \Auth::login($user,true);
    }
    
    public static function loginWithCredentials($username,$password){
        return (new Authentication())->login($username, $password);
    }
    
    protected function handleFailedLoginAttempt($user){
        
        $data = [];

        $time = getDateString();
        
        $msg="Invalid Login Credentials";
        
        if($user->failedLoginAttempts < $user->role->accountPolicy->failedLoginAttempts){
            $user->update(['failedLoginTime'=>$time,'failedLoginAttempts'=>$user->failedLoginAttempts+ 1]);
            $this->log($user->id, \EventType::LoginFail, "Failed Login Attempt", $this->username);
        }
        
        if($user->failedLoginAttempts >= $user->role->accountPolicy->failedLoginAttempts /*&& dateDiff($user->failedLoginTime) >= $user->role->accountPolicy->lockoutDuration*/){
            $user->update(['failedLoginTime'=>$time,'failedLoginAttempts'=>0,'lockoutThreshold'=>$user->lockoutThreshold +1]);
            $this->storeToCache(\AccountPolicyCodes::ThresholdKeyAbbr.$user->username, $user, $user->role->accountPolicy->lockoutDuration);
            $msg = 'Maximum number of failed Login Attempts reached.';
            $this->log($user->id, \EventType::LoginFail, "Maximum number of failed Login Attempts reached.", $this->username);
        }

        if($user->lockoutThreshold >= $user->role->accountPolicy->threshold){
            $data['lockoutThreshold'] = 0;
            $data['accountLocked'] = true;
            $this->log($user->id, \EventType::AccountLocked, "Account locked after too many failed login attempts", $this->username);
            $user->update(['failedLoginTime'=>$time,'accountLocked'=>true,'lockoutThreshold'=>0]);
            $this->storeToCache(\AccountPolicyCodes::LockoutKeyAbbr.$user->username, $user, $user->role->accountPolicy->reset);
            $msg= 'Your account has been locked due to multiple failed login attempts!';
        }

        return $this->failResponse($msg,'<h3>Authentication Fail</h3>');
        
    }
    
    protected function verifyUserStatus($user){

        $response = UserPolicy::getStatus($user);
        
        $data=[];
        
        switch($response){
            
            case \AccountPolicyCodes::AttemptsWait:
                return $this->buildResponse("Maximum number of failed Login Attempts reached.", 'danger', true,null,'<h3>Authentication Fail</h3>',$response);
            
            case \AccountPolicyCodes::LockedWait:
                $this->log($user->id, \EventType::AccountLocked, "Too many failed login attempts", $this->username);
                return $this->buildResponse("User Account Locked after too many failed login attempts", 'danger', true,null,'<h3>Authentication Fail</h3>',$response);
                
            case \AccountPolicyCodes::ClearLockedOut:
                $this->resetUserAccountPolicyRestrictions($user);
                return $this->buildResponse("User Account Locked after too many failed login attempts", 'danger', false,null,$response);
                
            case \AccountPolicyCodes::Success:
            default:
                return $this->buildResponse("", 'danger', false,null,$response);
        }
        
        
        
//        Auth::login($user);
//        
//        $this->resetUserAccountPolicyRestrictions($user);
//        
//        return $this->buildResponse("User LoggedIn Successfully");
        
    }
    
    protected function resetUserAccountPolicyRestrictions($user){
        $user->update(['failedLoginAttempts'=>0,'lockoutThreshold'=>0,'accountLocked'=>false,'failedLoginTime'=>null]);
    }
    

}
