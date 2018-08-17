<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use App\Http\Requests\NewUserRequest;
use Illuminate\Http\Request;
/**
 * Description of UserController
 *
 * @author FMCJr
 */
class UserController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function changePassword($id,Request $request){
        $res = $this->loginUser->usersRepo()->changePassword($id,$request->only(['oldPassword','newPassword']));
        return response()->json($res); 
    }
    
    public function createMobileKey(Request $request){
        $res = $this->loginUser->usersRepo()->createMobileKey($request->get('name'));
        return response()->json($res);
    }
    
    public function createUser(NewUserRequest $request){
        $res = $this->loginUser->usersRepo()->createUser($request->except(['_token','confirmPassword']));
        return response()->json($res);
    }
    
    public function deleteMobileKey($id){
        $res = $this->loginUser->usersRepo()->deleteMobileKey($id);
        return response()->json($res); 
    }
    
    public function deleteUser($id){
        $res = $this->loginUser->usersRepo()->deleteUser($id);
        return response()->json($res); 
    }
    
    public function editUser($id,NewUserRequest $request){
        $res = $this->loginUser->usersRepo()->editUser($id,$request->only(['username','role_id']));
        return response()->json($res);
    }
    
    public function getMobileKeys(){
        $res = $this->loginUser->usersRepo()->getMobileKeys();
        return response()->json($res);
    }
    
    public function getUsers(){
        $res = $this->loginUser->usersRepo()->getUsers();
        return response()->json($res);
    }
    
    public function refreshMobileKey($id){
        $res = $this->loginUser->usersRepo()->refreshMobileKey($id);
        return response()->json($res); 
    }
    
    public function resetPassword($id){
       $res = $this->loginUser->usersRepo()->resetPassword($id);
        return response()->json($res); 
    }
    
    public function suspendUser($id){
       $res = $this->loginUser->usersRepo()->suspendUser($id);
        return response()->json($res); 
    }
    
}
