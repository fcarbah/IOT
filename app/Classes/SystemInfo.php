<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes;

use App\Classes\CommandLine\Python;
/**
 * Description of SystemInfo
 *
 * @author FMCJr
 */
class SystemInfo {
    
    public $name;
    public $os;
    public $version;
    public $platform;
    public $lastBoot;
    public $cpu;
    public $disk;
    public $ram;
    public $isset = false;
    
    const GIGABYTE = 1073741824;
    
    public static function getInfo(){
        return \Cache::remember('sysinfo',15,function(){
            $res = Python::getInstance()->systemInfo();
            $info = new SystemInfo();
            if($res != null){
               $info->setInfo($res);
            }
            return $info;
        });
    }
    
    public function setInfo($object){
        $this->name = $object->system->name;
        $this->os = $object->system->system;
        $this->version = $object->system->version;
        $this->platform = $object->system->platform;
        $this->lastBoot = formatDate(2,(new \DateTime())->setTimestamp($object->system->boot));
        
        $this->cpu = (object)[
            'bits'=>$object->cpu->bits,
            'brand'=>$object->cpu->brand,
            'clock'=> number_format($object->cpu->hz_actual_raw[0]/1000000000,2),
            'cores'=>$object->cpu->count
        ];
        
        $this->disk = (object)[
            'total' => number_format($object->disk[0]/self::GIGABYTE,2),
            'used' => number_format($object->disk[1]/self::GIGABYTE,2),
            'free' => number_format($object->disk[2]/self::GIGABYTE,2),
            'percent'=>$object->disk[3]
        ];
        
        $this->ram = number_format($object->ram[0]/self::GIGABYTE,2);
        
        $this->isset = TRUE;
    }
    
}
