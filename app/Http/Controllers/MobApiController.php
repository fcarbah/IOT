<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\Camera;
/**
 * Description of MobApiController
 *
 * @author FMCJr
 */
class MobApiController extends Controller {
    
    
    public function __construct() {
        \App\Classes\Authentication::loginAsMobile();
    }
    
    public function index(){
        $res = \App\Classes\Dashboard::getInstance()->get();
        return response()->json($res);
    }
    
    public function getCamera(){
        $res = (new Camera())->get();
        return response()->json($res);
    }
    
    public function getMessages(Request $request){
        $res = \App\Classes\Repos\AlarmRepo::getInstance()->getMessages($request->get('recent',false),$request->get('limit',5));
        return response()->json($res);
    }
    
    public function photos(){
        $res = (new Camera())->photos();
        return response()->json($res);
    }
    
    public function takePhoto(){
        $res = (new Camera())->takePhoto();
        return response()->json($res);
    }
    
    public function turnCameraOff(){
        $res = (new Camera())->turnOff();
        return response()->json($res);
    }
    
    public function turnCameraOn(){
        $res = (new Camera())->turnOn();
        return response()->json($res);
    }
    
}
