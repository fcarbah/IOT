<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Traits;

use Illuminate\Support\Facades\Request;
/**
 * Description of AppLog
 *
 * @author FMCJr
 */
trait AppLog {
    
    public function log($userId,$eventCode,$notes='',$username=''){
        
        $eventTypes = eventTypes();
        
        $event = $eventTypes->where('code',$eventCode)->first();
        
        $eventId = $event != null? $event->id : 1;
        
        $ipAddress = Request::ip();
        
        \App\Models\Logs::create(['user_id'=>$userId,'event_type_id'=>$eventId,'notes'=>$notes,
            'username'=>$username,'ip'=>$ipAddress]);
        
        return true;
        
    }
    
}
