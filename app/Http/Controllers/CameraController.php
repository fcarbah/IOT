<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/**
 * Description of CameraController
 *
 * @author FMCJr
 */
class CameraController extends Controller {
   
    public function __construct() {
        parent::__construct();
    }
    
    public function deletePhoto(Request $request){
        $res = $this->loginUser->camera()->deletePhoto($request->all());
        return response()->json($res);
    }
    
    public function index(){
        $res = $this->loginUser->camera()->get();
        return response()->json($res);
    }
    
    public function photos(){
        $res = $this->loginUser->camera()->photos();
        return response()->json($res);
    }
    
    public function takePhoto(){
        $res = $this->loginUser->camera()->takePhoto();
        return response()->json($res);
    }
    
    public function turnOff(){
        $res = $this->loginUser->camera()->turnOff();
        return response()->json($res);
    }
    
    public function turnOn(){
        $res = $this->loginUser->camera()->turnOn();
        return response()->json($res);
    }
    
    
}
