<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Traits;

use App\Classes\AppResponse;
/**
 * Description of ResponseTrait
 *
 * @author fcarb
 */
trait ResponseTrait {
    
    public function buildResponse($messages=[],$type='success',$error=false,$data=null,$title='',$errorCode=0){
        return AppResponse::make($messages, $type, $error, $data,$title, $errorCode);  
    }
    
    public function permissionDenied(){
        return AppResponse::make('You do not have sufficient permissions', 'danger', true,'','<h4>Permission Denied</h4>');
    }
    
    public function successResponse($msg,$data=null,$title='<h4>Operation Succeded</h4>'){
        return AppResponse::make($msg, 'success', false,$data,$title);
    }
    
    public function failResponse($msg,$title='<h4>Operation Failed</h4>',$data=null){
        return AppResponse::make($msg, 'danger', true,$data,$title);
    }
    
}
