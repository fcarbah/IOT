<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Description of EventTypes
 *
 * @author FMCJr
 */
class EventTypes extends Model {
    
    protected $table='event_types';
    protected $primaryKey='id';
    protected $guarded = ['id'];
    
    public function logs(){
        return $this->hasMany('App\Models\Logs','event_type_id','id');
    }
    
}
