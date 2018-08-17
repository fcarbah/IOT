<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Traits;

use App\Classes\StorageObject;
/**
 * Description of StorageTrait
 *
 * @author FMCJr
 */
trait StorageTrait {
    
    public static function retrieveFromStorage($key){
        $_key = strtolower($key);

        if(session()->has($_key)){
            $item= session()->get($_key);
            $item->lastAccessed = getDateString();
            self::storeToSession($_key, $item);
            return $item;
        }        
        else if(\Cache::has($_key)){
            $item = \Cache::get($_key);
            $item->lastAccessed = getDateString();
            self::storeToCache($_key, $item, dateDiff($item->createdAt));
            return $item;
        }
        
    }
    
    public static function storeToCache($key,$data,$minutes=60){
        
        $object = new StorageObject();
        $object->item = $data;
        $object->createdAt = getDateString();        
        \Cache::add(strtolower($key),$object,$minutes);
    }
    
    public static function storeToSession($key,$data){
        $object = new StorageObject();
        $object->item = $data;
        $object->createdAt = getDateString();
        session()->put(strtolower($key),$object);
    }
    
}
