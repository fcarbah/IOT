<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes\System;

use App\Models\Alarms;
use App\Models\AlarmAlerts;
use App\Models\Temperatures;
use Carbon\Carbon;
use App\Classes\BroadcastMessage;
use App\Classes\Broadcaster;
/**
 * Description of Notifier
 *
 * @author FMCJr
 */
class Notifier {

    use \App\Traits\Notify;

    private $user;
    private $config;
    private static $self;

    private function __construct() {
        $this->user = auth()->user();
        $this->config = \App\Models\SysConfig::first();
        self::$self = $this;
    }

    public static function getInstance(){
        if(self::$self == null){
            self::$self = new Notifier();
        }
        return self::$self;
    }
    
    public function notify($contacts,$msg){
        
        if(!is_array($contacts) || ! $contacts instanceof Traversable){
            $contacts = [$contacts];
        }
        $options=['from'=>'noreply@mydevice.com','subject'=>'Device Alarm Cleared Notification'];
        $this->sendAlert($contacts, $msg, $options);
        
    }

    public function run(){
        $alarm = Alarms::active();

        if($alarm == null){
            return;
        }

        $task = new Tasks();
        if($task->clearActiveAlarm()){
            $options=['from'=>'noreply@mydevice.com','subject'=>'Device Alarm Cleared Notification'];
            $this->sendAlert(\App\Models\Contacts::contacts(), "Alarm Cleared", $options);
            return;
        }

        $temp = Temperatures::where('created_at','<',$alarm->created_at)
            ->orderBy('created_at','desc')->first();

        $location = \App\Classes\Dashboard::getInstance()->getLocation();

        $msg = 'Alarm Raised '. formatDate(1,$alarm->created_at).' Temperature Level Equals or Exceeds Threshold Limits.<br/>'
                .'Temperature: '.$temp->temp .'.<br/>Upper Threshold: '.$temp->upper.' | Lower Threshold: '.$temp->lower.'.<br/>Coordinates: '.
                $location->lat.', '.$location->lon.'.<br/>Altitude: '.$location->alt.'ft above sea level.<br/>Speed: '.$location->speed.'mph';

        //To Do:
        $this->notifyContacts($alarm,$msg);

        $this->notifyEmergency($alarm,$msg);

        $this->broadcastAlarm($alarm);

    }
    
    private function notifyContacts($alarm,$msg){
        $contacts = \App\Models\Contacts::contacts();

        if($contacts->count() <1){
            $this->notifyEmergency($alarm,$msg);
        }

        $lastAlert = AlarmAlerts::where('alarm_id',$alarm->id)->where('contactType','C')
            ->orderBy('created_at','desc')->first();

        $alertIntervals = json_decode($this->config->notification);

        $options=['from'=>'noreply@mydevice.com','subject'=>'Device Alarm Alert Notification'];

        if($lastAlert == null || dateDiff($lastAlert->created_at) >= $alertIntervals->contacts->id ){

            $this->sendAlert($contacts,$msg,$options);
            
            AlarmAlerts::create(['alarm_id'=>$alarm->id,'contactType'=>'C','sentStatus'=>true]);
        }


    }
    
    private function sendAlert($contacts,$msg,$options){
        foreach($contacts as $contact){
            $this->sendAlertByPreference($contact,$msg,$options);
        }
    }

    private function notifyEmergency($alarm,$msg){

        $contacts = \App\Models\Contacts::emergency();

        $lastAlert = AlarmAlerts::where('alarm_id',$alarm->id)->where('contactType','E')
            ->orderBy('created_at','desc')->first();

        $alertIntervals = json_decode($this->config->notification);

        $options=['from'=>'noreply@mydevice.com','subject'=>'Device Alarm Alert Notification'];

        if($lastAlert == null && dateDiff($alarm->created_at) >= $alertIntervals->emergency->id ){
            $this->sendAlert($contacts,$msg,$options);
            AlarmAlerts::create(['alarm_id'=>$alarm->id,'contactType'=>'E','sentStatus'=>true]);
        }
    }


    private function broadcastAlarm($alarm){

        $msg = 'Alarm Raised '. formatDate(1,$alarm->created_at).'. Temperature Level Equals or Exceed Threshold Limits';

        Broadcaster::broadcast(\BroadcastChannels::ClientAlarmNotification,new BroadcastMessage($msg,'','danger','Alarm Notification'));

        Broadcaster::broadcast(\BroadcastChannels::SystemAlarmOn,new BroadcastMessage($msg,'','danger','Alarm Notification'));
    }

    private function broadcastClearAlarm(){

    }

}
