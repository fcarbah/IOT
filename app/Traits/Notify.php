<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Traits;

/**
 * Description of Notifier
 *
 * @author FMCJr
 */
trait Notify {

    public function sendAlertByPreference(\App\Models\Contacts $contact,$message,$options=[]){

        $preferences = json_decode($contact->alertTypes);

        if(in_array('Call',$preferences)){
            //(new \App\Notifications\TwilioClient())->placeCall($contact->phone, $message);
            \App\Classes\CommandLine\Python::getInstance()->call($contact->phone);
        }

        if(in_array('Email',$preferences)){
            (new \App\Notifications\MailGunClient())->sendEmail($options['from'], $contact->email, $options['subject'],$message);
        }

        if(in_array('SMS',$preferences)){
            (new \App\Notifications\TwilioClient())->sendSMS($contact->phone, $message);
            
            \App\Classes\CommandLine\Python::getInstance()->sendSMS($contact->phone,$message);
        }
    }

}
