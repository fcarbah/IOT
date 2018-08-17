<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use \Illuminate\Database\Eloquent\Model;
/**
 * Description of Roles
 *
 * @author FMCJr
 */
class Roles extends Model {
    
    protected $table='roles';
    protected $primaryKey='id';
    protected $guarded = ['id'];
    
    public function accountPolicy(){
        return $this->hasOne('App\Models\AccountPolicy','role_id','id');
    }
    
    public function users(){
        return $this->hasMany('App\User')->withTrashed();
    }
   
    
}
