<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes;

use Illuminate\Support\Facades\Redis;

/**
 * Description of Broadcaster
 *
 * @author FMCJr
 */
class Broadcaster {
    
    public static function broadcast($channel, BroadcastMessage $data){
        Redis::publish($channel,json_encode($data));
    }
    
}
