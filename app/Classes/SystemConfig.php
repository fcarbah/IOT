<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes;

/**
 * Description of SystemConfig
 *
 * @author FMCJr
 */
class SystemConfig {
    
    public static function tempConfig(){
        return (object)[
            'lower'=>0,'upper'=>0
        ];
    }
    
    public static function defTempConfig(){
        return (object)[
            'lower'=>0,'upper'=>0,'min'=>0,'max'=>0
        ];
    }
    
    public static function securityConfig(){
        return (object)[
            'enableLocalNet'=>true,
            'enableIpFilter'=>true            
        ];
    }
    
    public static function wirelessConfig(){
        return (object)[
            'openNetworks'=> (object)['autoConnect'=>false],
            'useStaticIp'=>false
        ];
    }
    
    public static function notificationConfig(){
        return (object)[
            'contacts'=>(object)['id'=>5,'value'=>'Every 5 Minutes'],
            'emergency'=>(object)['id'=>10,'value'=>'After 10 Minutes']
        ];
    }
    
    public static function networkConfig(){
        return (object)[
            'mode'=>1,
            'staticIp'=> (object)[
                'ip'=>'','subnet'=>'','gateway'=>'','dns'=>'', 'dns2'=>''
            ],
            'dynamicIp'=>(object)[
                'ip'=>'','subnet'=>'','gateway'=>'','dns'=>'', 'dns2'=>''
            ],
            'mac'=>''
        ];
    }
    
    
    public static function deviceInfoConfig(){
        return (object)[
            'camera'=>(object)['on'=>0,'photo'=>0],
            'location'=>(object)['lat'=>'','lon'=>'','alt'=>'','speed'=>''],
            'driver'=>(object)['presence'=>0,'last'=>0,'pdate'=>'','last_pdate'=>'']
        ];
    }
    
}
