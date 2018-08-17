<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes\System;

use App\Models\Alarms;
use App\Models\Temperatures;
use Carbon\Carbon;
use App\Classes\BroadcastMessage;
use App\Classes\Broadcaster;
use App\Models\Messages;
/**
 * Description of Tasks
 *
 * @author FMCJr
 */
class Tasks {

    use \App\Traits\AppLog, \App\Traits\ResponseTrait;

    private $user;
    private $config;
    private $interval;
    private $threshold;

    public function __construct() {
        $this->user = auth()->user();
        $this->config = \App\Models\SysConfig::first();
        $this->interval = json_decode($this->config->notification);
        $this->threshold = json_decode($this->config->temperature);
    }

    public function addTemp(array $data){
        $data['temp'] = intval($data['temp']);
        $data['humidity'] = intval($data['humidity']);
        $data['presence'] = intval($data['presence']);
        $data['user_id'] = $this->user->id;

        $lastTemp = Temperatures::last();

        $temp = Temperatures::create($data);

        if($temp != null){

            if($this->shouldRaiseAlarm($temp, $lastTemp)){
                $alarm = $this->raiseAlarm($temp);

                $msg = 'Alarm Raised '. formatDate(1,$alarm->created_at).'. Temperature Level Equals or Exceed Threshold Limits <br/>Temperature: <strong>'.$temp->temp.'</strong> ';
                $msg.='<br/>Lower Limit: <strong>'.$this->threshold->lower.'</strong> | Upper Limit: <strong>'.$this->threshold->upper.'</strong>';
                $message = Messages::create(['message'=>$msg,'title'=>'Alarm Activated',
                    'event_type_id'=> \EventType::AlarmRaised,'user_id'=>$this->user->id]);

                $this->broadcastAlarm($alarm,$temp,$message);
            }
            else{
                $this->broadcastTempUpdate($temp);
            }

            if($this->shouldTurnOffAlarm()){
                $this->clearAlarm();
            }

        }

    }
    
    public function driverPresence(array $data){
        
        $devInfo = \App\Models\DeviceInfo::first();
        
        if($data['driver']== 0 && $data['presence'] == 1 && $data['notify'] == 'True'){
            sleep(10);
            $msg ='Child inside vehicle';
            Broadcaster::broadcast(\BroadcastChannels::ClientAlarmNotification,new BroadcastMessage($msg,'','info','Child Alert'));
        }
        
        $driverInfo = json_decode($devInfo->driver);
        $driverInfo->last = $driverInfo->presence;
        $driverInfo->last_pdate = $driverInfo->pdate;
        $driverInfo->presence = $data['driver'];
        $driverInfo->pdate = getDateString();
        
        $devInfo->driver = json_encode($driverInfo);
        $devInfo->save();
    }
    
    public function clearActiveAlarm(){
        if($this->shouldTurnOffAlarm()){
            $this->clearAlarm();
            return true;
        }
        return false;
    }

    public function location(array $data){
        $config = \App\Models\DeviceInfo::first();
        $config->location = json_encode((object)[
            'lat'=> floatval($data['lat']),
            'lon'=>floatval($data['lon']),
            'alt'=> floatval($data['alt']),
            'speed'=>floatval($data['speed']),
        ]);
        $config->save();
        $res = json_decode($config->location);
        $res->lastUpdate = getDateString($config->updated_at);
        Broadcaster::broadcast(\BroadcastChannels::LocationUpdate,new BroadcastMessage('','','','','',$res));
    }

    public function presence(array $data){
        
        $lastTemp = Temperatures::last();
        $presence = $data['presence'];
        if($lastTemp != null){
            $lastTemp->presence = intval($presence);
            $lastTemp->save();
        }
        
        if(isset($data['broadcast']) && $data['broadcast'] == 'True'){
            Broadcaster::broadcast(\BroadcastChannels::PresenceUpdate,new BroadcastMessage('','','','','',$presence));
        }

        Broadcaster::broadcast(\BroadcastChannels::SystemPresence,new BroadcastMessage('','','','','',$presence));
    }

    protected function activeAlarm(){
        $activeAlarm = Alarms::active();

        if($activeAlarm == null){
            return null;
        }

        $oldTemps = Temperatures::where('created_at','>=',Carbon::now()->subMinutes(12))
            ->where('created_at','<=',Carbon::now()->subMinutes(2))
            ->get();

        $reset = true;

        foreach($oldTemps as $temp){
            if($temp->temp <= $this->threshold->lower || $temp->temp >= $this->threshold->upper){
                $reset=false;
                break;
            }
        }

        if($reset){
            $this->log($this->user->id, \EventType::AlarmCleared,'Alarm Cleared by System. Temperature returned to normal');
            return null;
        }

        return $activeAlarm;
    }

    protected function broadcastTempUpdate($temp){
        $chart = \App\Classes\Dashboard::getInstance()->getChartData();
        $data = (object)['temp'=>$temp,'threshold'=>json_decode($this->config->temperature),'chart'=>$chart];
        Broadcaster::broadcast(\BroadcastChannels::TempUpdate,new BroadcastMessage('','','','','',$data));
    }

    protected function broadcastAlarm($alarm,$temp,$message){

        if($alarm != null){
            $alarm->duration = formatDate(1,$alarm->created_at);
        }

        $msg = 'Alarm Raised '. formatDate(1,$alarm->created_at).'. Temperature Level Equals or Exceed Threshold Limits <br/>Temperature: <strong>'.$temp->temp.'</strong> ';
        $msg.='<br/>Lower Limit: <strong>'.$this->threshold->lower.'</strong> | Upper Limit: <strong>'.$this->threshold->upper.'</strong>';

        Broadcaster::broadcast(\BroadcastChannels::ClientAlarmNotification,new BroadcastMessage($msg,'','danger','Alarm Notification'));

        Broadcaster::broadcast(\BroadcastChannels::SystemAlarmOn,new BroadcastMessage($msg,'','danger','Alarm Notification'));

        $notif = \App\Models\Messages::getMessage($message->id);
        $chart = \App\Classes\Dashboard::getInstance()->getChartData();
        
        Broadcaster::broadcast(\BroadcastChannels::ClientAlarmData, new BroadcastMessage('','','','','',
            (object)['temp'=>$temp,'alarm'=>$alarm,'chart'=>$chart,'threshold'=>json_decode($this->config->temperature)]));

        Broadcaster::broadcast(\BroadcastChannels::ClientAlarmNotification, new BroadcastMessage('','','','','',$notif));
    }

    protected function clearAlarm(){
        $activeAlarm = Alarms::active();

        if($activeAlarm != null){
            $activeAlarm->resolvedBy = $this->user->id;
            $activeAlarm->status = false;
            $activeAlarm->isActive = false;
            $activeAlarm->save();
        }

        $this->deactivateOlderAlarms();
        $msg = 'Alarm Cleared. The temperature has been outside the Threshold limits for the past'. $this->interval->emergency->id.' minutes';
        $message = Messages::create(['message'=>$msg,'title'=>'Alarm Cleared','event_type_id'=> \EventType::AlarmCleared,'user_id'=>$this->user->id]);
        Broadcaster::broadcast(\BroadcastChannels::ClientAlarmOff, new BroadcastMessage($msg,'','success','Alarm Deactivated','',(object)['alarm'=>$activeAlarm]));
        Broadcaster::broadcast(\BroadcastChannels::ClientAlarmOffNotif, new BroadcastMessage($msg,'','success','Alarm Deactivated',''));
        Broadcaster::broadcast(\BroadcastChannels::SystemAlarmOff, new BroadcastMessage($msg,'','success','Alarm Deactivated'));
    }

    protected function deactivateOlderAlarms(){
        \DB::table('alarms')->update(['isActive'=>FALSE,'status'=>FALSE]);
    }

    protected function raiseAlarm($temp){
        $this->deactivateOlderAlarms();
        $this->log($this->user->id, \EventType::AlarmRaised,'Alarm Raised. Temperature Equals or Exceed Threshold. ('. $temp->temp.')');
        return Alarms::create(['status'=>true,'isActive'=>true,'raisedBy'=>$this->user->id]);
    }

    protected function shouldRaiseAlarm($temp,$lastTemp){

        if($lastTemp == null){
            return false;
        }

        if(($lastTemp->temp > $this->threshold->lower && $lastTemp->temp < $this->threshold->upper)
            || ($temp->temp > $this->threshold->lower && $temp->temp <$this->threshold->upper) ){
            return false;
        }

        if($lastTemp->presence == 0 || $temp->presence == 0){
          return false;
        }

        $activeAlarm = $this->activeAlarm();

        if($activeAlarm == null){
            return true;
        }

        if(dateDiff($activeAlarm->created_at) < 30){
            return false;
        }

        return true;

    }

    protected function shouldTurnOffAlarm(){
        $alarm = Alarms::active();
        if($alarm == null){
            return false;
        }

        $temps = Temperatures::where('created_at','>=',$alarm->created_at)
            ->orderBy('created_at','desc')->take($this->interval->emergency->id)->get();

        $lastTemp = Temperatures::last();

        if($temps->count()< $this->interval->emergency->id || ($lastTemp != null && dateDiff($alarm->created_at,$lastTemp->created_at) < $this->interval->emergency->id)){
            return false;
        }

        /*if($lastTemp != null && dateDiff($alarm->created_at,$lastTemp->created_at) >= $this->interval->emergency->id){
            return true;
        }*/

        foreach($temps as $temp){
            if(($temp->temp <= $this->threshold->lower || $temp->temp >= $this->threshold->upper) && $temp->presence == 1){
                return false;
            }

        }

        return true;

    }

}
