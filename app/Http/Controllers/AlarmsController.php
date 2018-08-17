<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/**
 * Description of AlarmsController
 *
 * @author FMCJr
 */
class AlarmsController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getMessages(Request $request){
        $res = $this->loginUser->alarmRepo()->getMessages($request->get('recent',false),$request->get('limit',5));
        return response()->json($res);
    }
    
}
