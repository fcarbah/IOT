<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Description of Logs
 *
 * @author FMCJr
 */
class Logs extends Model {
    
    protected $table='logs';
    protected $primaryKey='id';
    protected $guarded = ['id'];
    
    public function eventType(){
        return $this->belongsTo('App\Models\EventTypes', 'event_type_id', 'id');
    }
    
    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id')->withTrashed();
    }
    
}
