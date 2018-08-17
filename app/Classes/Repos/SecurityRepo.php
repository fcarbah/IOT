<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes\Repos;

/**
 * Description of SecurityRepo
 *
 * @author FMCJr
 */
class SecurityRepo {
    
    use \App\Traits\ResponseTrait,\App\Traits\PermissionTrait,\App\Traits\AppLog;
    
    private $apModel;
    private $aclModel;
    private $user;
    private static $self;
    
    public static function getInstance(){
        if(self::$self == null){
            self::$self = new SecurityRepo();
        }
        return self::$self;
    }
    
    public function __construct() {
        $this->apModel = new \App\Models\AccountPolicy();
        $this->aclModel = new \App\Models\AccessControl();
        $this->user = auth()->user();
        self::$self = $this;
    }
    
    public function addIpFilter(array $data){
        
        if(!$this->hasPermission('security.acl.add', $this->user)){
            return $this->permissionDenied();
        }
        
        $octet =1;
        if(!isValidIpRange($data['startIp'],$data['endIp'],$octet)){
            return $this->failResponse('IP Range Error. Start IP cannot be greater than End IP','<h3>Validation Error</h3>');
        }
        
        $data['createdBy'] = $this->user->id;
        $data['updatedBy'] = $this->user->id;
        $data['compareOctet'] = $octet-1;
        
        $filter = $this->aclModel->create($data);
        
        if($filter != null){
            $this->log($this->user->id, \EventType::AddFilter, 'ACL Rule added', $this->user->username);
            return $this->successResponse('Ip Filter Added',$this->filters());
        }
        return $this->failResponse('Error Adding IP Filter');
    }
    
    public function deleteIpFilter($filterId){
        
        if(!$this->hasPermission('security.acl.delete', $this->user)){
            return $this->permissionDenied();
        }
        
        $filter = $this->aclModel->find($filterId);
        
        if($filter != null){
            $filter->delete();
            $this->log($this->user->id, \EventType::DeleteFilter, 'ACL Rule deleted', $this->user->username);
            return $this->successResponse('IP Filter Deleted',$this->filters());
        }
        return $this->failResponse('Invalid IP Filter');
    }
    
    public function editIpFilter($filterId,array $data){
        
        if(!$this->hasPermission('security.acl.edit', $this->user)){
            return $this->permissionDenied();
        }
        $octet =1;
        if(!isValidIpRange($data['startIp'],$data['endIp'],$octet)){
            return $this->failResponse('IP Range Error. Start IP cannot be greater than End IP','<h3>Validation Error</h3>');
        }
        
        $filter = $this->aclModel->find($filterId);
        
        if($filter != null){
            $data['compareOctet'] = $octet-1;
            $data['updatedBy'] = $this->user->id; 
            $filter->update($data);
            $this->log($this->user->id, \EventType::AddFilter, 'ACL Rule updated', $this->user->username);
            return $this->successResponse('Ip Filter Updated',$this->filters());
        }
        return $this->failResponse('Invalid IP Filter');
    }
    
    public function getAccountPolicies(){
        return $this->successResponse('',$this->policies());
    }
    
    public function getFilters(){
        return $this->successResponse('',$this->filters());
    }
    
    public function updateAccountPolicy($policyId, array $data){
        
        if(!$this->hasPermission('security.ap.edit', $this->user)){
            return $this->permissionDenied();
        }
        
        $policy = $this->apModel->find($policyId);
        
        if($policy != null){
            $data['updatedBy'] = $this->user->id;
            $policy->update($data);
            
            $this->log($this->user->id, \EventType::UpdateAP, $policy->name.' Account Policy Modified', $this->user->username);
            
            return $this->successResponse($policy->name.' Updated',$this->policies());
        }
        return $this->failResponse('Invalid Policy');
    }
    
    protected function filters(){
        $filters = $this->aclModel->all();
        $config = SystemRepo::getInstance()->getSecurityConfig()->data;
        $modes = accessModes();
        return (object)['filters'=>$filters,'config'=>$config,'modes'=>$modes];
    }
    
    protected function policies(){   
        $policies = $this->apModel->with('role')->get()
            ->map(function($item){
                return (object)['id'=>$item->id,'name'=>$item->name,'role_id'=>$item->role_id,
                    'role'=>$item->role->name,'lockoutDuration'=>$item->lockoutDuration,
                    'threshold'=>$item->threshold,'reset'=>$item->reset,'failedLoginAttempts'=>$item->failedLoginAttempts,
                    'createdBy'=>$item->createdBy,'updatedBy'=>$item->updatedBy,
                    'created_at'=>$item->created_at->format('Y-m-d H:i:s'),'updated_at'=>$item->updated_at->format('Y-m-d H:i:s')];
            })
            ->all();
        
            $roles = \App\Models\Roles::all(); 
            
        return (object)['policies'=>$policies,'lockoutDurations'=> lockoutDurations(),'userRoles'=>$roles,
            'resetDurations'=> resetDurations(), 'counts'=>[1,2,3,4,5]];
    }
    
    
}
