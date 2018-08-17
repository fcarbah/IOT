<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/**
 * Description of ConfigController
 *
 * @author FMCJr
 */
class ConfigController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getDeviceInfo(){
        $res = $this->loginUser->systemRepo()->getDeviceInfo();
        return response()->json($res);
    }
    
    public function getNetworkConfig(){
        $res = $this->loginUser->systemRepo()->getNetworkConfig();
        return response()->json($res);
    }
    
    public function getNotifConfig(){
        $res = $this->loginUser->systemRepo()->getNotifConfig();
        return response()->json($res);
    }
    
    public function getSetup(){
        $res = $this->loginUser->systemRepo()->getInitialSetup();
        return response()->json($res);
    }
    
    public function getSecurityConfig(){
        $res = $this->loginUser->systemRepo()->getSecurityConfig();
        return response()->json($res);
    }
    
    public function getTempConfig(){
        $res = $this->loginUser->systemRepo()->getTempConfig();
        return response()->json($res);
    }
    
    public function getWirelessConfig(){
        $res = $this->loginUser->systemRepo()->getWirelessConfig();
        return response()->json($res);
    }
    
    public function logs(){
        $res = $this->loginUser->systemRepo()->getLogs();
        return response()->json($res);
    }
    
    public function saveNetworkConfig(Request $request){
        $res = $this->loginUser->systemRepo()->saveNetworkConfig($request->all());
        return response()->json($res);
    }
    
    public function saveNotifConfig(Request $request){
        $res = $this->loginUser->systemRepo()->saveNotifConfig($request->all());
        return response()->json($res);
    }
    
    public function saveSetup(Request $request){
        $res = $this->loginUser->systemRepo()->saveInitialSetup($request->all());
        return response()->json($res);
    }
    
    public function saveSecurityConfig(Request $request){
        $res = $this->loginUser->systemRepo()->saveSecurityConfig($request->all());
        return response()->json($res);
    }
    
    public function saveTempConfig(Request $request){
        $res = $this->loginUser->systemRepo()->saveTempConfig($request->all());
        return response()->json($res);
    }
    
    public function saveWirelessConfig(Request $request){
        $res = $this->loginUser->systemRepo()->saveWirelessConfig($request->all());
        return response()->json($res);
    }
    
}
