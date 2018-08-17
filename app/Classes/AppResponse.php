<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes;

/**
 * Description of AppResponse
 *
 * @author FMCJr
 */
class AppResponse {
    
    public $type;
    public $title;
    public $messages;
    public $data;
    public $error;
    public $errorCode;
    public $redirect = False;
    public $redirectUrl ='';
    public $redirectState='';
    
    public static function make($messages=[],$type='success',$error=false,$data=null,$title='Notification',$errorCode=0){
        $response = new AppResponse();
        
        if(is_array($messages)){
            $response->messages = $messages;
        }
        else{
            $response->messages = [$messages];
        }
        $response->type = $type;
        $response->title = $title;
        $response->error = $error;
        $response->data = $data;
        $response->errorCode = $errorCode;
        
        return $response;
    }
    
    
    public function messageStr($glue='.'){
        return implode($glue,$this->messages);
    }
    
}
