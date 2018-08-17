<?php

/*
* To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

namespace App\Classes;

/**
* Description of NetInfo
*
* @author FMCJr
*/
class NetInfo {
    
	public $ip;
	public $subnet;
	public $gateway;
	public $mac;
	public $dns;
	public $dns2;
    
	private $ipPattern ='/\d{1,3}(\.\d{1,3}){3}/';
	private $macPattern = '/\w{2}(\:\w{2}){5}/';
        private $metric = 1000;
    
	public function convert($ipinfo){

            $gateway = $this->setGateway($ipinfo->gateways);
            
            if($gateway ==null){
                return;
            }
            $ipinfo->gateway = $gateway;
            $this->gateway = $ipinfo->gateway->ip;
            $interface = $ipinfo->gateway->interface;
            $cidr = intval($ipinfo->gateway->cidr);
            $this->ip = $ipinfo->address->$interface->ipv4;
            $this->subnet = $this->getSubnet($cidr);
            $this->mac =$ipinfo->address->$interface->mac;
            $this->dns  = count($ipinfo->dns) >0 ? $ipinfo->dns[0] : '';
            $this->dns2  = count($ipinfo->dns) >1 ? $ipinfo->dns[1] : '';
	}
        
        private function setGateway($gateways){
            $indx = 0;
            $selGateway = null;
            foreach($gateways as $key=>$gw){
                if(isset($gw->metric) && $gw->metric < $this->metric){
                    $selGateway = $gw;
                    $this->metric = $gw->metric;
                    $indx=$key+1;
                }
                else{
                    break;
                }
            }
            
            if($selGateway != null){
                for($i = $indx;$i<count($gateways);$i++){
                    if($gateways[$i]->interface == $selGateway->interface){
                        $selGateway->cidr = $gateways[$i]->cidr;
                        break;
                    }
                } 
                
            }
            
            return $selGateway;
            
        }
        
        private function getSubnet($cidr){
            $p = intval($cidr/8);
            $r = $cidr%8;
            $rem = 32 - $cidr;

            $subnet='';
            while($cidr != 0){
                if($cidr >=8){
                    $subnet .= bindec('11111111').'.';
                    $cidr -= 8;	 	 	 
                }
                else{
                    $subnet .=bindec(array_fill(0,$cidr,'1')).'.';
                    $cidr -= $cidr;
                }
            }
            $cidr = $rem;
            while($cidr != 0){
                if($cidr >=8){
                    $subnet .= bindec('00000000').'.';
                    $cidr -= 8;	 	 	 
                }
                else{
                    $subnet .=bindec(array_fill(0,$cidr,'0')).'.';
                    $cidr -= $cidr;
                }	 	 	 
            }
            return substr($subnet,0,-1);
	 }    
    
	private function setIPInfo($object,$properties){
            foreach($properties as $prop){
                if(is_array($object->$prop)){
                        $props = get_object_vars($object->$prop);
                        $this->setIPInfo($object->$prop,$props);
                }else{
                    if(isset($object->$prop->addr) && preg_match($this->macPattern,$object->$prop->addr)){
                            $this->mac = $object->$prop->addr;
                    }
                    if(isset($object->$prop->addr) && preg_match($this->ipPattern,$object->$prop->addr)){
                            $this->ip = $object->$prop->addr;
                    }  
                    if(isset($object->$prop->netmask) && preg_match($this->ipPattern,$object->$prop->netmask)){
                            $this->subnet = $object->$prop->netmask;
                    }
                }
            }
	}
    
	private function setGateways($object,$properties){
            if($this->gateway != null){
                return;
            }
            foreach($properties as $prop){
                if(!is_array($object->$prop)){
                    $props = get_object_vars($object->$prop);
                    $this->setIPInfo($object->$prop,$props);
                }else if(is_array($object->$prop)){
                    foreach ($object->$prop as $val){
                        if(preg_match($this->ipPattern,$val)){
                            $this->gateway = $val;
                        }
                    }
                }
            }
	}
    
}
