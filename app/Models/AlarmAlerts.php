<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Description of AlarmAlerts
 *
 * @author FMCJr
 */
class AlarmAlerts extends Model{
    
    public $table='alarm_alerts';
    public $primaryKey='id';
    public $guarded = ['id'];
    
    public function alarm(){
        return $this->belongsTo('App\Models\Alarms', 'alarm_id', 'id');
    }
    
    public function contacts(){
        //return $this->belongsTo('App\User', 'user_id', 'id');
    }
    
}
