<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes;

/**
 * Description of UserAccount
 *
 * @author FMCJr
 */
class UserAccount {
    
    protected $user;
    protected $permissions;
    protected $role;
    protected static $self;    
    
    public function __construct($user){
        if(self::$self != null){
            return self::$self;
        }
        $this->user = $user;
        $this->role = $this->user->role;
        $this->permissions = Permissions::getPermission($this->role->id);
        self::$self = $this;
    }
    
    public static function getInstance(){
        if(self::$self == null){
            self::$self = new UserAccount();
        }
        return self::$self;
    }
    
    public function me(){
        return (object)[
            'user'=>$this->user,
            'permissions'=>$this->permissions,
        ];        
    }
    
    public function alarmRepo(){
        return Repos\AlarmRepo::getInstance();
    }
    
    public function camera(){
        return new Camera();
    }
    
    public function contactsRepo(){
        return new Repos\ContactRepo();
    }
    
    public function dashboard(){
        return Dashboard::getInstance();
    }
    
    public function networkRepo(){
        return new Repos\NetworkRepo();
    }

    public function permissions(){
        return $this->permissions;
    }
    
    public function securityRepo(){
        return new Repos\SecurityRepo();
    }
    
    public function systemRepo(){
        return new Repos\SystemRepo();
    }
    
    public function user(){
        return $this->user;
    }
    
    public function usersRepo(){
        return new Repos\UserRepo();
    }
    
    protected function hasPermission(){
        
    }
    
}
