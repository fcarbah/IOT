<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes\Repos;

use App\Classes\CommandLine\Python;
use App\Classes\NetInfo;
/**
 * Description of SystemRepo
 *
 * @author FMCJr
 */
class SystemRepo {

    use \App\Traits\ResponseTrait,\App\Traits\PermissionTrait,\App\Traits\AppLog;

    private $config;
    private $user;
    private static $self;

    public static function getInstance(){
        if(self::$self == null){
            self::$self = new SystemRepo();
        }
        return self::$self;
    }

    public function __construct() {
        $this->config = \App\Models\SysConfig::first();
        $this->user = auth()->user();
        self::$self = $this;
    }

    public function getDeviceInfo(){
        $info = \App\Classes\SystemInfo::getInfo();

        $db = \App\Classes\Dashboard::getInstance();

        $data = ['sysinfo'=>$info,'env'=>$db->getEnvironment(),
            'alarm'=>$db->getAlarm(),'location'=>$db->getLocation()];

        //if($info->isset){
        return $this->successResponse('',$data);
        //}

        //return $this->failResponse('Failed to retrieve device information');
    }
    
    public function getInitialSetup(){
        $info = \App\Models\DeviceInfo::first();
        
        if ($info != null && $info->setup_complete){
            return $this->successResponse('', (object)['complete'=>true]);
        }
        
        $temp = $this->tempConfig();
        $notif = $this->notifConfig();
        $owner = $info->owner_info != null? json_decode($info->owner_info) : (object)['name'=>'','phone'=>'','email'=>''];

        return $this->successResponse('', (object)['complete'=>false,'temp'=>$temp,'notif'=>$notif,'owner'=>$owner]);
        
    }

    public function getNetworkConfig(){
       if($this->config != null){
            $data = $this->networkConfig();
            return $this->successResponse('',$data);
        }
        return $this->failResponse('Network Configuration is missing');
    }

    public function getNotifConfig(){
        if($this->config != null){
            $data = $this->notifConfig();
            return $this->successResponse('',$data);
        }
        return $this->failResponse('Notification Configuration is missing');
    }

    public function getSecurityConfig(){
       if($this->config != null){
            return $this->successResponse('',$this->securityConfig());
        }
        return $this->faillResponse('Security Configuration is missing');
    }

    public function getTempConfig(){
        if($this->config != null){
            $data = $this->tempConfig();
            return $this->successResponse('',$data);
        }
        return $this->failResponse('Temperature Configuration is missing');
    }

    public function getWirelessConfig(){
        if($this->config != null){
            return $this->successResponse('',$this->wirelessConfig());
        }
        return $this->failResponse('Wireless Configuration is missing');
    }

    public function getLogs(){
        $accessLogs = \App\Models\Logs::whereIn('event_type_id',[2,3,4,5,6])->with('eventType','user')
        ->orderBy('created_at','desc')->get();

        $data = (object)['accessLogs'=>$accessLogs];

        return $this->successResponse('',$data);

    }
    
    public function saveInitialSetup(array $data){
        
        $info = \App\Models\DeviceInfo::first();
        
        if($info != null && $info->setup_complete){
            return $this->buildResponse(['Setup has already been completed'], 'warning', false);
        }
        
        $tempConfig = $data['temp'];
        $ownerInfo = $data['owner'];
        $notifConfig = $data['notif'];
        $contactInfo = array_merge($ownerInfo,['type'=>'Contacts','alertTypes'=>['Call','Email','SMS']]);
        
        
        $resTemp = $this->saveTempConfig($tempConfig);
        $resNotif = $this->saveNotifConfig($notifConfig);
        $resContact = ContactRepo::getInstance()->addContact($contactInfo);
        
        if(!$resTemp->error && !$resContact->error && !$resNotif->error){

            $info->owner_info = json_encode($ownerInfo);
            $info->temp_info = json_encode($tempConfig);
            $info->notif_info = json_encode($notifConfig);
            $info->setup_step = 3;
            $info->setup_complete = true;
            $info->save();
            
            return $this->successResponse('Setup Complete');
            
        }
        
        $msg = [];
        $msg = $resContact->error? array_merge($msg,$resContact->messages) : $msg;
        $msg = $resTemp->error? array_merge($msg,$resTemp->messages) : $msg;
        $msg = $resNotif->error? array_merge($msg,$resNotif->messages) : $msg;
        
        return $this->failResponse($msg, "<h3>Setup Failed<h3>");
        
        
        
    }
    
    public function saveNetworkConfig(array $data){

        if(!$this->hasPermission('configuration.network.edit', $this->user)){
            return $this->permissionDenied();
        }

        regexReplaceArray($data,'/\_/','');
        regexReplaceArray($data,'/\.{3}/','');

        $data['updatedBy'] = $this->user->id;
        $config = (object)$data;
        $this->config->network = json_encode($config);
        $this->config->updatedBy = $this->user->id;
        $this->config->save();

        $this->log($this->user->id, \EventType::NetworkUpdate, 'Network Settings Modified', $this->user->username);

        $python = Python::getInstance();
        $python->setIpInfo($data);

        return $this->successResponse("Network Configuration Saved",$this->networkConfig());
    }

    public function saveNotifConfig(array $data){

        if(!$this->hasPermission('configuration.notifications.edit', $this->user)){
            return $this->permissionDenied();
        }

        $data['updatedBy'] = $this->user->id;
        $config = (object)$data;
        $this->config->notification = json_encode($config);
        $this->config->updatedBy = $this->user->id;
        $this->config->save();

        $this->log($this->user->id, \EventType::NotificationUpdate, 'Notification Settings Modified', $this->user->username);
        return $this->successResponse("Notification Configuration Saved",$this->notifConfig());
    }

    public function saveSecurityConfig(array $data){

        if(!$this->hasPermission('security.acl.edit', $this->user)){
            return $this->permissionDenied();
        }

        $data['updatedBy'] = $this->user->id;
        $config = (object)$data;
        $this->config->security = json_encode($config);
        $this->config->updatedBy = $this->user->id;
        $this->config->save();

        $this->log($this->user->id, \EventType::SecurityUpdate, 'Security Settings Modified', $this->user->username);
        return $this->successResponse("Security Configuration Saved", $this->securityConfig());
    }

    public function saveTempConfig(array $data){

        if(!$this->hasPermission('configuration.temperature.edit', $this->user)){
            return $this->permissionDenied();
        }

        $shouldBroadcast = false;

        $oldThreshold = json_decode($this->config->temperature);

        if($oldThreshold->lower != $data['lower'] || $oldThreshold->upper != $data['upper']){
            $shouldBroadcast = true;
        }

        $data['updatedBy'] = $this->user->id;
        $config = (object)$data;
        $this->config->temperature = json_encode($config);
        $this->config->updatedBy = $this->user->id;
        $this->config->save();

        $this->log($this->user->id, \EventType::TempUpdate, 'Temperature Threshold Settings Modified', $this->user->username);

        if($shouldBroadcast){
            \App\Classes\Broadcaster::broadcast(\BroadcastChannels::SystemTempChange, new \App\Classes\BroadcastMessage('Temp Threshold Updated','success'));
        }

        return $this->successResponse("Temperature Configuration Saved",$this->tempConfig());
    }

    public function saveWirelessConfig(array $data){

        if(!$this->hasPermission('configuration.wireless.edit', $this->user)){
            return $this->permissionDenied();
        }

        $data['updatedBy'] = $this->user->id;
        $config = (object)$data;
        $this->config->wireless = json_encode($config);
        $this->config->updatedBy = $this->user->id;
        $this->config->save();

        $this->log($this->user->id, \EventType::WirelessUpdate, 'Wireless Settings Modified', $this->user->username);
        return $this->successResponse("Wireless Configuration Saved", $this->wirelessConfig());
    }

    protected function networkConfig(){

        $conf = json_decode($this->config->network);
        //To do: Get local ip Info
        $cmd = Python::getInstance();
        $info = $cmd->getIpInfo();
        $netInfo = new NetInfo();
        if($info != null){
            $netInfo->convert($info);
        }

        return(object)['mode'=>$conf->mode,'ipInfo'=>$conf->staticIp,'networkModes'=> networkModes(),'dynamicIp'=>$netInfo,'mac'=>$netInfo->mac];
    }

    protected function notifConfig(){
        $conf = json_decode($this->config->notification);
        return(object)['conf'=>$conf,'alertIntervals'=> alertIntervals()];
    }

    protected function securityConfig(){
        return json_decode($this->config->security);
    }

    protected function tempConfig(){
        $temp = json_decode($this->config->temperature);
        $baseTemp = json_decode($this->config->defTemperature);
        return (object)['temp'=>$temp,'baseTemp'=>$baseTemp];
    }

    protected function wirelessConfig(){
       return json_decode($this->config->wireless);
    }

}
