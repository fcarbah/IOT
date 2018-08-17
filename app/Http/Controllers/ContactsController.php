<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
/**
 * Description of ContactsController
 *
 * @author FMCJr
 */
class ContactsController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function addContact(ContactRequest $request){
       $res = $this->loginUser->contactsRepo()->addContact($request->except(['_token']));
       return response()->json($res);
    }
    
    public function deleteContact($id){
       $res = $this->loginUser->contactsRepo()->deleteContact($id);
       return response()->json($res);
    }
    
    public function getContacts(){
        $res = $this->loginUser->contactsRepo()->getContacts();
        return response()->json($res);
    }
     
    public function updateContact($id,ContactRequest $request){
       $res = $this->loginUser->contactsRepo()->updateContact($id,$request->except(['_token','id']));
       return response()->json($res);
    }

}
