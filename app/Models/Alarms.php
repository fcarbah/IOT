<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Description of Alarms
 *
 * @author FMCJr
 */
class Alarms extends Model {
    
    protected $table='alarms';
    protected $primaryKey='id';
    protected $guarded = ['id'];
    
    public $duration;
    
    public static function active(){
        return self::where('isActive',True)->first();
    }
    
    public function alerts(){
        return $this->hasMany('App\Models\Alarms', 'alarm_id', 'id');
    }

    public function initiator(){
        return $this->belongsTo('App\User', 'raisedBy', 'id')->withTrashed();
    }
    
    public static function last(){
        return self::orderBy('created_at','desc')->first();
    }
    
    public function resolver(){
        return $this->belongsTo('App\User', 'resolvedBy', 'id')->withTrashed();
    }
    
}
