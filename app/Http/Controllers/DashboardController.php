<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

/**
 * Description of DashboardController
 *
 * @author FMCJr
 */
class DashboardController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $res = $this->loginUser->dashboard()->get();
        return response()->json($res);
    }
}
