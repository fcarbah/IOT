<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Traits;

/**
 * Description of PermissionTrait
 *
 * @author FMCJr
 */
trait PermissionTrait {

    public function hasPermission(string $permissionFullName,\App\User $user){
        return (bool)$this->effictivePermission($permissionFullName, $user);
    }

    public function hasRequiredPermission(string $permissionFullName,\App\User $user,$otherUserId){
        $permission = $this->effictivePermission($permissionFullName, $user);

        if($permission ===0){
            return false;
        }

        if($permission===1){
            return true;
        }

        $otherUser = \App\User::find($otherUserId);

        if($otherUser != null && $permission===2 && $user->role_id<=$otherUser->role_id){
            return true;
        }

        if($otherUser != null && $permission===3 && $user->id==$otherUser->id){
            return true;
        }

        return false;
    }

    public function hasUserPermission(string $permissionFullName,\App\User $user,$roleId){

        $permission = $this->effictivePermission($permissionFullName, $user);

        if($permission ==0){
            return false;
        }

        if($permission==1){
            return true;
        }

        return $user->role_id <= $roleId;
    }


    private function effictivePermission(string $permissionFullName,\App\User $user){
        $permissions = \App\Classes\Permissions::getPermission($user->role_id);

        $pNames= explode('.',$permissionFullName);
        $hasPermission = false;

        foreach($pNames as $key){
            if(isset($permissions->{$key})){
               $hasPermission = $permissions->$key;
               $permissions = $hasPermission;
            }else{
                $hasPermission = false;
                break;
            }
        }

        return $hasPermission;
    }

}
