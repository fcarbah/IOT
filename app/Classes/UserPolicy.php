<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes;

/**
 * Description of UserPolicy
 *
 * @author FMCJr
 */
class UserPolicy {
    
    private $user;
    private $policy;
    
    
    public function __construct($user){
        $this->user = $user;
        $this->policy = $this->user->role->accountPolicy;
    }
    
    protected function init(){
       
        if($this->user->accountLocked && dateDiff($this->user->failedLoginTime) < $this->policy->reset){
            return \AccountPolicyCodes::LockedWait;
        }
        else if($this->user->accountLocked && dateDiff($this->user->failedLoginTime) >= $this->policy->reset){
            return \AccountPolicyCodes::ClearLockedOut;
        }
        /*elseif($this->user->lockoutThreshold < $this->policy->threshold && dateDiff($this->user->failedLoginTime) >= $this->policy->lockoutDuration ){
            return \AccountPolicyCodes::IncrementThreshold;
        }
        elseif($this->user->lockoutThreshold >= $this->policy->threshold){
            return \AccountPolicyCodes::LockedOut;
        }*/
        elseif($this->user->failedLoginAttempts < $this->policy->failedLoginAttempts){
            return \AccountPolicyCodes::Success;
        }
        elseif($this->user->failedLoginAttempts >= $this->policy->failedLoginAttempts && dateDiff($this->user->failedLoginTime) >= $this->policy->lockoutDuration){
            return \AccountPolicyCodes::AttemptsWait;
        }
        
        return \AccountPolicyCodes::Success;
    }
    
    public static function getStatus($user){
        return (new UserPolicy($user))->init();
    }
    
}
