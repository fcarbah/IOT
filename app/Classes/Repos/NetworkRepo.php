<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes\Repos;

use App\Classes\CommandLine\Python;

/**
 * Description of NetworkRepo
 *
 * @author FMCJr
 */
class NetworkRepo {
    
    use \App\Traits\ResponseTrait,\App\Traits\PermissionTrait,\App\Traits\AppLog;
    
    private $wireless;
    private $user;
    private static $self;
    
    public static function getInstance(){
        if(self::$self == null){
            self::$self = new NetworkRepo();
        }
        return self::$self;
    }
    
    public function __construct() {
        $this->wireless = new \App\Models\Wireless();
        $this->user = auth()->user();
        self::$self = $this;
    }
    
    public function addWirelessNetwork(array $data){

        if(!$this->hasPermission('configuration.wireless.add', $this->user)){
            return $this->permissionDenied();
        }

        $data['updatedBy'] = $this->user->id;
        $data['createdBy'] = $this->user->id;

        $network = $this->wireless->create($data);

        if($network != null){
            $this->log($this->user->id, \EventType::WirelessAdd, 'Wireless network added', $this->user->username);
            
            (new Python())->wifiTasks('add',$data);
            
            $ndata = $this->networks();
            
            return $this->successResponse('Wireless Network Added Successfully',$ndata);
        }
        
        return $this->failResponse('Error Adding Wireless Network "'.$data['ssid'].'"');
       
    }
    
    public function changeWifiPassword($wId,array $data){
        
        if(!$this->hasPermission('configuration.wireless.edit', $this->user)){
            return $this->permissionDenied();
        }
        
        $network = $this->wireless->find($wId);
        
        if($network != null){
           $data['updatedBy'] = $this->user->id;
           $network->update($data);
           
           $this->log($this->user->id, \EventType::WirelessEdit, 'Wifi network '.$network->ssid.' password changed', $this->user->username);
           
           (new Python())->wifiTasks('edit',['ssid'=>$network->ssid,'password'=>$network->password,'authType'=>$network->authType,'oldssid'=>$network->ssid]);
           
           return $this->successResponse('Wifi Password Changed');
        }
        return $this->failResponse('Invalid Wireless Network');
    }

    public function deleteWirelessNetwork($wId){
        
        if(!$this->hasPermission('configuration.wireless.delete', $this->user)){
            return $this->permissionDenied();
        }
        
        $network = $this->wireless->find($wId);
        
        if($network != null){
           $network->delete();
           
           $this->log($this->user->id, \EventType::WirelessEdit, 'Wifi network '.$network->ssid.' deleted', $this->user->username);
           
           (new Python())->wifiTasks('delete',['ssid'=>$network->ssid,'password'=>$network->password,'authType'=>$network->authType]);
           
           return $this->successResponse('Wifi Network Deleted',$this->networks());
        }
        return $this->failResponse('Invalid Wireless Network');
    }
    
    public function getWirelessNetworks(){
        
        $data = $this->networks();
        return $this->buildResponse('','success',false,$data);
    }
    
    public function updateWirelessNetwork($wId,array $data){
        
        if(!$this->hasPermission('configuration.wireless.edit', $this->user)){
            return $this->permissionDenied();
        }
        
        $network = $this->wireless->find($wId);
                
        if($network != null){
            $old = $network->ssid;
            $data['updatedBy'] = $this->user->id;
            $network->update($data);
            $this->log($this->user->id, \EventType::WirelessEdit, 'Wifi '.$network->ssid.' updated', $this->user->username);
            (new Python())->wifiTasks('edit',['oldssid'=>$old,'password'=>$network->password,'authType'=>$network->authType,'ssid'=>$data['ssid']]);
            $ndata = $this->networks();
            return $this->successResponse('Wireless Network "'.$data['ssid'].'" Updated',$ndata);
        }
        return $this->failResponse('Invalid Wireless Network');
    }
   
    protected function networks(){
        $networks = $this->wireless->all();
        $authTypes = wirelessAuthModes();
        $config = (new SystemRepo())->getWirelessConfig()->data;
        
        return (object)['networks'=>$networks,'authTypes'=>$authTypes,'config'=>$config];
        
    }
    
    
}
