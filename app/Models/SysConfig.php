<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Description of SysConfig
 *
 * @author FMCJr
 */
class SysConfig extends Model {
    
    protected $table='system_config';
    protected $primaryKey='id';
    protected $guarded = ['id'];
    
    
}
