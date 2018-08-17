<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Description of Temperatures
 *
 * @author FMCJr
 */
class Temperatures extends Model {
    
    protected $table='temperatures';
    protected $primaryKey='id';
    protected $guarded = ['id'];
        
    public static function last(){
        return self::orderBy('created_at','desc')->first();
    }
    
    public static function recent($limit=10){
        return self::orderBy('created_at','desc')->take($limit)->get();
    }
    
}
