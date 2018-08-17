<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;
use App\Classes\System\Tasks;
use Illuminate\Http\Request;
/**
 * Description of LocalController
 *
 * @author FMCJr
 */
class LocalController extends Controller {
    
    public function __construct() {
        \App\Classes\Authentication::loginAsSystem();
    }
    
    public function __destruct() {
        session()->flush();
    }
    
    public function addTemperature(Request $request){
        (new Tasks())->addTemp($request->all());
    }
    
    public function driverPresence(Request $request){
        (new Tasks())->driverPresence($request->all());
    }
    
    public function location(Request $request){
        (new Tasks())->location($request->all());
    }
    
    public function updatePresence(Request $request){
        (new Tasks())->presence($request->all());
    }
    
}
