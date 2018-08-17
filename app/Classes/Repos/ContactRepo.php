<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes\Repos;

/**
 * Description of ContactRepo
 *
 * @author FMCJr
 */
class ContactRepo {
    
    use \App\Traits\ResponseTrait,\App\Traits\PermissionTrait,\App\Traits\AppLog;
    
    private $contacts;
    private $user;
    private static $self;
    
    public static function getInstance(){
        if(self::$self == null){
            self::$self = new ContactRepo();
        }
        return self::$self;
    }
    
    public function __construct() {
        $this->contacts = new \App\Models\Contacts();
        $this->user = auth()->user();
        self::$self = $this;
    }
    
    public function addContact(array $data){
        
        if(!$this->hasPermission('configuration.contacts.add', $this->user)){
            return $this->permissionDenied();
        }
        
        $data['createdBy']= $this->user->id;
        $data['updatedBy']= $this->user->id;
        $data['alertTypes'] = json_encode($data['alertTypes']);
        
        $contact = $this->contacts->create($data);
        
        if($contact != null){
            $this->log($this->user->id, \EventType::AddContact, 'New '.$data['type'].' contact '.$data['name'].' added', $this->user->username);
            
            return $this->successResponse('New Contact Added',$this->contacts());
        }
        return $this->failResponse('Error Adding Contact "'.$data['username'].'"');
    }
     
    public function deleteContact($contactId){
        
        if(!$this->hasPermission('configuration.contacts.delete', $this->user)){
            return $this->permissionDenied();
        }
        
        $contact = $this->contacts->find($contactId);
        if($contact != null){
            
            $msg = $contact->name.' deleted';
            $contact->delete();
            
            $this->log($this->user->id, \EventType::DeleteContact, 'Contact '.$contact->name.' deleted', $this->user->username);
            
            return $this->successResponse($msg,$this->contacts());
        }
        return $this->failResponse('Invalid Contact');
    }
    
    public function getContacts(){
        return $this->buildResponse('', 'success', false,$this->contacts());
    }
    
    public function updateContact($contactId,array $data){
        
        if(!$this->hasPermission('configuration.contacts.edit', $this->user)){
            return $this->permissionDenied();
        }
        
        $contact = $this->contacts->find($contactId);
        
        if($contact != null){
           $data['updatedBy'] = $this->user->id;
           $data['alertTypes'] = json_encode($data['alertTypes']);
           $contact->update($data);
           
           $this->log($this->user->id, \EventType::AddContact, 'Contact '.$data['type'].' updated', $this->user->username);
           
           return $this->successResponse('Contact Updated',$this->contacts());
        }
        
        return $this->failResponse('Invalid Contact');
    }
   
    protected function contacts(){
        $contacts = $this->contacts->all()
            ->map(function($item){
                $item->alertTypes = json_decode($item->alertTypes);
                return $item;
            })
            ->all();
        $alertTypes = alertTypes();
        $types = contactTypes();
        
        return (object)['alertTypes'=>$alertTypes,'contacts'=>$contacts,'types'=>$types];
    }
    
}
