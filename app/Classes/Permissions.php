<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes;

/**
 * Description of Permissions
 *
 * @author FMCJr
 */
class Permissions {
    
    public $camera;
    public $dashboard;
    public $configuration;
    public $security;
    public $users;
    public $device;
    public $mobile;

    private function __construct(){
        
    }
    
    public static function getPermission($role){
       
        if(\Roles::Admin===$role){
            return (new Permissions())->adminPermissions();
        }
        
        if(\Roles::PowerUser===$role){
            return (new Permissions())->powerUsersPermissions();
        }

        return (new Permissions())->userPermissions();
        
    }
    
    protected function adminPermissions(){
        
        $this->camera = (object)[
            'view'=>true,
            'photo'=>(object)['capture'=>true,'delete'=>true]
        ];
        
        $this->configuration = (object)[
            'view'=>true,
            'temperature'=>(object)['view'=>true,'edit'=>true],
            'notifications'=>(object)['view'=>true,'edit'=>true],
            'contacts'=>(object)['view'=>true,'add'=>true,'edit'=>true,'delete'=>true],
            'network'=>(object)['view'=>true,'edit'=>true],
            'wireless'=>(object)['view'=>true,'add'=>true,'edit'=>true,'delete'=>true]
        ];
        
        $this->mobile = (object)[
            'view'=>true,
            'keys'=>(object)['view'=>true,'add'=>true,'edit'=>true,'delete'=>true]
        ];
        
        $this->security = (object)[
            'view'=>true,
            'acl'=>(object)['view'=>true,'add'=>true,'edit'=>true,'delete'=>true],
            'ap'=>(object)['view'=>true,'add'=>false,'edit'=>true,'delete'=>false]
        ];
        
        $this->users =(object)[
            'view'=>true,
            'manage'=>(object)['view'=>1,'edit'=>1,'add'=>1,'delete'=>1,'resetPassword'=>1,
                'changePassword'=>3,'suspend'=>1]
        ];
        
        $this->device = (object)[
            'view'=>true,
            'logs'=>(object)['view'=>1],
            'info'=>(object)['view'=>true,'update'=>true,'rollback'=>true]
        ];
        
        $this->dashboard = (object)[
            'view'=>true
        ];
        
        return $this;
    }
    
    protected function powerUsersPermissions(){
        $this->camera = (object)[
            'view'=>true,
            'photo'=>(object)['capture'=>true,'delete'=>true]
        ];
        
        $this->configuration = (object)[
            'view'=>true,
            'temperature'=>(object)['view'=>true,'edit'=>true],
            'notifications'=>(object)['view'=>true,'edit'=>true],
            'contacts'=>(object)['view'=>true,'add'=>true,'edit'=>true,'delete'=>true],
            'network'=>(object)['view'=>true,'edit'=>true],
            'wireless'=>(object)['view'=>true,'add'=>true,'edit'=>true,'delete'=>true]
        ];
        
        $this->mobile = (object)[
            'view'=>true,
            'keys'=>(object)['view'=>true,'add'=>true,'edit'=>true,'delete'=>true]
        ];
        
        $this->security = (object)[
            'view'=>true,
            'acl'=>(object)['view'=>true,'add'=>true,'edit'=>true,'delete'=>false],
            'ap'=>(object)['view'=>true,'add'=>false,'edit'=>true,'delete'=>false]
        ];
        
        $this->users =(object)[
            'view'=>true,
            'manage'=>(object)['view'=>1,'edit'=>2,'add'=>1,'delete'=>2,'resetPassword'=>2,
                'changePassword'=>3,'suspend'=>2]
        ];
        
        $this->device = (object)[
            'view'=>true,
            'logs'=>(object)['view'=>1],
            'info'=>(object)['view'=>true,'update'=>true,'rollback'=>true]
        ];
        
        $this->dashboard = (object)[
            'view'=>true
        ];
        
        return $this;
    }
    
    protected function userPermissions(){
        
        $this->camera = (object)[
            'view'=>true,
            'photo'=>(object)['capture'=>true,'delete'=>false]
        ];
        
        $this->configuration = (object)[
            'view'=>false,
            'temperature'=>(object)['view'=>false,'edit'=>false],
            'notifications'=>(object)['view'=>false,'edit'=>false],
            'contacts'=>(object)['view'=>false,'add'=>false,'edit'=>false,'delete'=>false],
            'network'=>(object)['view'=>false,'edit'=>false],
            'wireless'=>(object)['view'=>false,'add'=>false,'edit'=>false,'delete'=>false]
        ];
        
        $this->mobile = (object)[
            'view'=>false,
            'keys'=>(object)['view'=>false,'add'=>false,'edit'=>false,'delete'=>false]
        ];
        
        $this->security = (object)[
            'view'=>false,
            'acl'=>(object)['view'=>false,'add'=>false,'edit'=>false,'delete'=>false],
            'ap'=>(object)['view'=>false,'add'=>false,'edit'=>false,'delete'=>false]
        ];
        
        $this->users =(object)[
            'view'=>false,
            'manage'=>(object)['view'=>1,'edit'=>3,'add'=>0,'delete'=>3,'resetPassword'=>3,
                'changePassword'=>3,'suspend'=>0]
        ];
        
        $this->device = (object)[
            'view'=>true,
            'logs'=>(object)['view'=>3],
            'info'=>(object)['view'=>true,'update'=>false,'rollback'=>false]
        ];
        
        $this->dashboard = (object)[
            'view'=>true
        ];
        
        return $this;
    }

    
}
