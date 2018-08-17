<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes\CommandLine;

/**
 * Description of Python
 *
 * @author FMCJr
 */
class Python {
    
    private $config;
    private $python;
    private $appBase;
    
    private static $self;
    
    public function __construct() {
        $this->config = (object)\Config::get('appconfig.python');
        $this->python = $this->config->exe;
        $this->appBase = $this->config->appbase;
        self::$self = $this;
    }
    
    public static function getInstance(){
        if(self::$self==null){
            self::$self = new Python();
        }
        return self::$self;
    }
    
    public function call($phoneNumber,$message=""){
        $command = $this->python.' '.$this->appBase.$this->config->phone." \"call\" \"$phoneNumber\" \"$message\" ";
        $res =shell_exec($command);

        if($res != null){
            return json_decode($res);
        } 
    }

    public function cameraTasks($action){
        

        $command = $this->python.' '.$this->appBase.$this->config->camera." \"$action\"";
        $res =shell_exec($command);
        
        //sudo python3.4 /home/pi/iotapp/Apps/Network/wlan.py "add" "{\"ssid\":\"shdisd\",\"authType\":\"Open (No Encryption)\",\"password\":\"\",\"autoConnect\":true}"
        //sudo python3.4 /home/pi/iotapp/Apps/Network/wlan.py "edit" "{\"ssid\":\"Linksys\",\"password\":null,\"authType\":\"WEP (Shared Network Key)\"}"
        if($res != null){
            return json_decode($res);
        }
    }
    
    public function getIpInfo(){
        $command = $this->python.' '.$this->appBase.$this->config->netinfo;
        $res =shell_exec($command);

        if($res != null){
            return json_decode($res);
        }	 
    }
    
    public function sendSMS($phoneNumber,$message){
        $command = $this->python.' '.$this->appBase.$this->config->phone." \"sms\" \"$phoneNumber\" \"$message\" ";
        $res =shell_exec($command);

        if($res != null){
            return json_decode($res);
        } 
    }
    
    public function setIpInfo(array $data){
        $object = json_decode(json_encode($data));
        $dns =[];
        
        if($object->staticIp->dns != null){
            $dns[] = $object->staticIp->dns;
        }
        if($object->staticIp->dns2 != null){
            $dns[] = $object->staticIp->dns2;
        }
        
        $newObject = (object)['mode'=>$object->mode,
            'ip'=>$object->staticIp->ip,'subnet'=>$object->staticIp->subnet,
            'gateway'=>$object->staticIp->gateway,
            'dns'=>$dns
        ];
        //sudo python3.4 /home/pi/iotapp/Apps/Network/static.py "{\"mode\":1,\"ip\":\"\",\"subnet\":\"\",\"gateway\":\"\",\"dns\":[\"\",\"\"]}"
        $command = $this->python.' '.$this->appBase.$this->config->setNetInfo.' '.json_encode(json_encode($newObject));
        $res =shell_exec($command);
        
        if($res != null){
            return json_decode($res);
        }	
        
    }
    
    public function systemInfo(){
        $command = $this->python.' '.$this->appBase.$this->config->sysinfo;
        $res =shell_exec($command);

        if($res != null){
            return json_decode($res);
        }
    }
    
    public function wifiTasks($action, array $data){
        
        if($data['authType'] == \WirelessAuthenticationTypes::Open || $data['authType'] == \WirelessAuthenticationTypes::MAC){
            $data['password'] = '';
        }
        
        $command = $this->python.' '.$this->appBase.$this->config->wifi." \"$action\" ".json_encode(json_encode($data));
        $res =shell_exec($command);
        
        //sudo python3.4 /home/pi/iotapp/Apps/Network/wlan.py "add" "{\"ssid\":\"shdisd\",\"authType\":\"Open (No Encryption)\",\"password\":\"\",\"autoConnect\":true}"
        //sudo python3.4 /home/pi/iotapp/Apps/Network/wlan.py "edit" "{\"ssid\":\"Linksys\",\"password\":null,\"authType\":\"WEP (Shared Network Key)\"}"
        if($res != null){
            return json_decode($res);
        }
    }
    
}
