<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes\Repos;

/**
 * Description of UserRepo
 *
 * @author FMCJr
 */
class UserRepo {
    
    use \App\Traits\ResponseTrait,\App\Traits\PermissionTrait,\App\Traits\AppLog;
    
    private $model;
    private $mobileKeysModel;
    private $user;
    private static $self;
    
    public static function getInstance(){
        if(self::$self == null){
            self::$self = new Security();
        }
        return self::$self;
    }
    
    public function __construct() {
        $this->model = new \App\User();
        $this->mobileKeysModel = new \App\Models\MobileKeys();
        $this->user = auth()->user();
        self::$self = $this;
    }
    
    public function changePassword($userId,array $data){

        if(!$this->hasRequiredPermission('users.manage.changePassword', $this->user,$userId)){
            return $this->permissionDenied();
        }

        $user = $this->model->find($userId);
        
        if($user != null){
            if(!\Hash::check($data['oldPassword'],$user->password)){
                return $this->failResponse('Old password does not match current password');
            }
            $user->password = \Hash::make($data['newPassword']);
            $user->save();
            $this->log($this->user->id, \EventType::UserEdit, $user->username.' password changed', $this->user->username);
            return $this->successResponse($user->username.' Password Changed');
        }
        return $this->failResponse('Invalid User');
    }
    
    public function createMobileKey($name){
        if(!$this->hasPermission('mobile.keys.add', $this->user)){
            return $this->permissionDenied();
        }
        
        $key = generateRandomChars(mt_rand(10,20));
        
        $mobileKey = $this->mobileKeysModel->create(['key'=>$key,'name'=>$name,'user_id'=>$this->user->id,'updatedBy'=>$this->user->id]);
        
        if($mobileKey != null){
            return $this->successResponse('Mobile Key Created Successfully', $this->mobileKeysModel->all());
        }
        
        return $this->failResponse('Error Creating Key');
    }
    
    public function createUser(array $data){
        
        if(!$this->hasUserPermission('users.manage.add', $this->user,$data['role_id'])){
            return $this->permissionDenied();
        }
        
        $data['password'] = \Hash::make($data['password']);
        $user = $this->model->create($data);
        
        if($user != null){
            $this->log($this->user->id, \EventType::UserAdd, $user->username.' Account Created', $this->user->username);
            return $this->successResponse('User "'.$data['username'].'" Created',$this->users());
        }
        return $this->failResponse('Error Creating User');
    }
    
    public function deleteMobileKey($id){
        if(!$this->hasPermission('mobile.keys.delete', $this->user)){
            return $this->permissionDenied();
        }
        
        $mobileKey = $this->mobileKeysModel->find($id);
        
        if($mobileKey != null){
            $mobileKey->delete();
            return $this->successResponse('Mobile Key deleted Successfully', $this->mobileKeysModel->all());
        }
        return $this->failResponse("Invalid Mobile Key");
    }
    
    public function deleteUser($userId){
        
        if(!$this->hasRequiredPermission('users.manage.delete', $this->user,$userId)){
            return $this->permissionDenied();
        }
        
        $user = $this->model->find($userId);
        
        if($user != null){
            
            if($user->isProtected || $user->id == $this->user->id){
                return $this->failResponse('Cannot Delete User');
            }
            $user->delete();
            $this->log($this->user->id, \EventType::UserDelete, $user->username.' Account Deleted', $this->user->username);
            return $this->successResponse($user->username.' Deleted',$this->users());
        }
        return $this->failResponse('Invalid User');
    }
    
    public function editUser($userId,array $data){
        
        if(!$this->hasUserPermission('users.manage.edit', $this->user,$data['role_id'])){
            return $this->permissionDenied();
        }
        
        $user = $this->model->find($userId);
        
        if($user != null){
            $user->update($data);
            $this->log($this->user->id, \EventType::UserEdit, $user->username.' Account Updated', $this->user->username);
            return $this->successResponse('User "'.$data['username'].'" Updated',$this->users());
        }
        
        return $this->failResponse('Invalid User');
    }
    
    public function getMobileKeys(){
        return $this->successResponse('', $this->mobileKeysModel->all());
    }
    
    
    public function getUsers(){
        return $this->successResponse('',$this->users());
    }
    
    public function refreshMobileKey($id){
        if(!$this->hasPermission('mobile.keys.delete', $this->user)){
            return $this->permissionDenied();
        }
        
        $mobileKey = $this->mobileKeysModel->find($id);
        
        if($mobileKey != null){
            $key = generateRandomChars(mt_rand(10,20));
            $mobileKey->update(['key'=>$key,'updatedBy'=>$this->user->id]);
            return $this->successResponse('Mobile Key regenerated successfully', $this->mobileKeysModel->all());
        }
        return $this->failResponse("Invalid Mobile Key");
    }
    
    public function resetPassword($userId){
        
        if(!$this->hasRequiredPermission('users.manage.suspend', $this->user,$userId)){
            return $this->permissionDenied();
        }
        
        $user = $this->model->find($userId);
        
        if($user != null && !$user->isProtected){
            $user->password = \Hash::make('password');
            $user->save();
            $this->log($this->user->id, \EventType::UserAdd, $user->username.' password reset', $this->user->username);
            return $this->successResponse($user->username.' Password reset to "password"');
        }
        return $this->failResponse('Invalid User');
    }
    
    public function suspendUser($userId){
        
        if(!$this->hasRequiredPermission('users.manage.suspend', $this->user,$userId)){
            return $this->permissionDenied();
        }
        
        $user = $this->model->find($userId);

        if($user != null && !$user->isProtected){
            $user->update(['status'=>0,'canLogin'=>0]);
            $this->log($this->user->id, \EventType::UserAdd, $user->username.' Account Suspended', $this->user->username);
            return $this->successResponse($user->username.' Account Suspended. User will not be able to login',$this->users());
        }
        return $this->buildResponse('Invalid User', 'danger',true,'','<h3>Operation Failed</h3>');
    }
    
    protected function users(){
        
        $users = $this->model->where('isProtected',false)->with('role')->get()
            ->map(function($item){
                return (object)['id'=>$item->id,'username'=>$item->username,'role_id'=>$item->role_id,
                    'role'=>$item->role->name,'status'=>(bool)$item->status,'canLogin'=>(bool)$item->canLogin,
                    'canEdit'=>(bool)$item->canEdit];
            })
            ->all();
        
        if($this->user->role_id==1){
            $roles = \App\Models\Roles::all();
        }elseif($this->user->role_id==2){
            $roles = \App\Models\Roles::whereIn('id',[2,3])->get();
        }else{
            $roles=[];
        }
        
        return (object)['users'=>$users,'userRoles'=>$roles];
    }
    
}
