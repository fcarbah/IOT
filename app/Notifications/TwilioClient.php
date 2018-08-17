<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Notifications;

use Aloha\Twilio\Twilio;

use Services_Twilio;

/**
 * Description of TwilioClient
 *
 * @author fcarb
 */
class TwilioClient {
    
    protected $twilio;
    
    protected $twilioService;
    
    public function __construct() {
        //$this->twilio = new Twilio(getenv('twilio.sid'),getenv('twilio.token'),getenv('twilio.phone'),false);
        
        $this->twilioService = new Services_Twilio(getenv('twilio.sid'),getenv('twilio.token'));
    }
    
    public function sendSMS($phonenumber,$message){
   
        try{

            $this->twilioService->account->messages->sendMessage(getenv('twilio.phone'), $phonenumber, $this->formatMessage($message));

        } catch (Exception $ex) {
            
        }
    }
    
    public function placeCall($phonenumber,$msg){

        try{
            
            $formattedMessage = $this->formatMessage($msg);
            
            $twiml = (new \Services_Twilio_Twiml());
            
            $xml = "<?xml version='1.0' encoding='UTF-8'?><Response>".(string) $twiml->say($formattedMessage)."</Response>";
            
            //upload xml file
            $uploader = new AWSClient();
            $uploader->uploadFile('twilio.xml', $xml,'text/xml');
            
            $url = $uploader->getURL();

            if($url != null){
                $this->twilioService->account->calls->create(getenv('twilio.phone'),$phonenumber,$url,['Method'=>'GET']);
            }
            
        } catch (Exception $ex) {
            
        }
    }
    
    protected function formatMessage($message){
        if(!is_array($message)){
            return preg_replace("/<br\/>/"," ",$message);
        }
        
        $result ='';
        foreach($message as $key=>$msg){
            if(strtolower($key) != 'oncall' && strtolower($key) != 'ids' && strtolower($key) != 'from'){
                $result .= $msg.". ";
            }
        }

        return preg_replace("/<br\/>/"," ",$result);
    }
    
    protected function saveXMLFile($xmlmessage){
        \File::put("attachments/twilio.xml",$xmlmessage);
    }
    
    protected function deleteXMLFile($filename){
        \File::delete("attachments/twilio.xml");
    }
    
}
