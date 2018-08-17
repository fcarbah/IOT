<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * Description of Messages
 *
 * @author FMCJr
 */
class Messages extends Model {

    protected $table='messages';
    protected $primaryKey='id';
    protected $guarded = ['id'];

    public function eventType(){
        return $this->belongsTo('App\Models\EventTypes', 'event_type_id', 'id');
    }

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id')->withTrashed();
    }

    public static function recent($limit=5){
        $dateStr = getDateString(null,1);
        return self::orderBy('created_at','desc')->whereRaw("date(created_at) >= '$dateStr'")
          ->take($limit)->get();
    }

    public static function getMessage($id){
        return self::with('user','eventType')->where('id',$id)->first();
    }

}
