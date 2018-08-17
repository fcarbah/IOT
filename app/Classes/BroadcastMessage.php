<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes;

/**
 * Description of BroadcastMessage
 *
 * @author FMCJr
 */
class BroadcastMessage {
    
    public $title;
    public $type;
    public $alert;
    public $sender;
    public $message;
    public $data;
    public $channel;
    public $showMessage= true;
    
    public function __construct($message,$type,$alert='info',$title='',$sender='',$data=''){
        $this->type = $type;
        $this->title = $title;
        $this->alert = $alert;
        $this->message = $message;
        $this->sender = $sender;
        $this->data = $data;
    }
    
}
