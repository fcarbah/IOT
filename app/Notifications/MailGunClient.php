<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Notifications;

use Unirest\Request;
/**
 * Description of MailGunClient
 *
 * @author fcarb
 */
class MailGunClient {

    protected $key;
    
    protected $domain;
    
    protected $from;
    
    protected $baseURi = "https://api.mailgun.net/v3/";
    
    public function __construct() {
        
        $this->key = env('mailgun.secret');
        $this->domain = env('mailgun.domain');
        $this->from = env('mailgun.from');
    
    }
    
    
    public function sendEmail($from,$to,$subject,$text){
        
        $data = ['from'=>$this->from,'to'=>$to,'subject'=>$subject,'html'=>$text];
        
        Request::verifyPeer(false);
        
        Request::post($this->buildURL('messages'),['Authorization'=>'Basic '.base64_encode("api:$this->key")],$data);

    }
    
    protected function buildURL($endpoint){
        return $this->baseURi.$this->domain."/$endpoint";
    }
    
}
