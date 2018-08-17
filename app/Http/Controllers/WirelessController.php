<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\WirelessRequest;
/**
 * Description of WirelessController
 *
 * @author FMCJr
 */
class WirelessController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function addNetwork(WirelessRequest $request){
        $res = $this->loginUser->networkRepo()->addWirelessNetwork($request->except(['_token']));
        return response()->json($res);
    }
    
    public function changePassword($id,Request $request){
        $res = $this->loginUser->networkRepo()->changeWifiPassword($id,$request->only(['password']));
        return response()->json($res);
    }
    
    public function deleteNetwork($id){
        $res = $this->loginUser->networkRepo()->deleteWirelessNetwork($id);
        return response()->json($res);
    }
    
    public function getNetworks(){
        $res = $this->loginUser->networkRepo()->getWirelessNetworks();
        return response()->json($res);
    }
    
    public function updateNetwork($id,Request $request){
        $res = $this->loginUser->networkRepo()->updateWirelessNetwork($id,$request->except(['_token','id','password']));
        return response()->json($res);
    }
    
}
