<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Description of AccountPolicy
 *
 * @author FMCJr
 */
class AccountPolicy extends Model {
    
    protected $table='account_policies';
    protected $primaryKey='id';
    protected $guarded = ['id'];
    
    public function role(){
        return $this->belongsTo('App\Models\Roles');
    }
    
}
