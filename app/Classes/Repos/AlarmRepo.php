<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes\Repos;

/**
 * Description of AlarmRepo
 *
 * @author FMCJr
 */
class AlarmRepo {

    use \App\Traits\ResponseTrait,\App\Traits\PermissionTrait,\App\Traits\AppLog;

    const SystemId = 1;

    private $alarmModel;
    private $alertModel;
    private $messageModel;
    private $user;
    private static $self;

    public static function getInstance(){
        if(self::$self == null){
            self::$self = new AlarmRepo();
        }
        return self::$self;
    }

    private function __construct() {
        $this->alarmModel = new \App\Models\Alarms();
        $this->alertModel = new \App\Models\AlarmAlerts();
        $this->messageModel = new \App\Models\Messages();
        $this->user = auth()->check()? auth()->user() : \App\User::find(self::SystemId);
        self::$self = $this;
    }

    public function activeAlarm(){

    }

    public function addAlarm(array $data){

    }

    public function getAlarms(){

    }

    public function getMessages($recent=false,$limit=5){
        $notifs = $recent==false? $this->messageModel->with('user')->orderBy('created_at','desc')->get() : $this->messageModel->recent($limit); 
        return $this->buildResponse('', 'success', false, $notifs);
    }

    public function resolveAlarm($alarmId,array $data){

    }

    protected function addMessage(){

    }

}
