<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Description of Wireless
 *
 * @author FMCJr
 */
class Wireless extends Model {
    
    protected $table='wireless_networks';
    protected $primaryKey='id';
    protected $guarded = ['id'];
    protected $hidden =['password'];
}
