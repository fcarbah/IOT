<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Description of Contacts
 *
 * @author FMCJr
 */
class Contacts extends Model {
    
    protected $table='contacts';
    protected $primaryKey='id';
    protected $guarded = ['id'];
    
    public static function contacts(){
        return self::where('type','Contacts')->get();
    }
    
    public static function emergency(){
        return self::where('type','Emergency')->get();
    }
    
}
