<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes;
use BroadcastChannels;
use App\Classes\Broadcaster;
use App\Classes\BroadcastMessage;
use App\Classes\CommandLine\Python;
/**
 * Description of Camera
 *
 * @author FMCJr
 */
class Camera {
    use \App\Traits\ResponseTrait,\App\Traits\PermissionTrait;
    
    private $config;
    private $cam;
    
    public function __construct(){
        $this->config = \App\Models\DeviceInfo::first();
        $this->cam = json_decode($this->config->camera);
    }
    
    public function deletePhoto(array $data){
        
        if(!$this->hasPermission('camera.photo.delete',auth()->user())){
            return $this->permissionDenied();
        }
        
        if(unlink(public_path().$data['path'])){
            return $this->successResponse('Image "'.$data['name'].'" deleted successfully',$this->loadPhotos());
        }
        return $this->failResponse('Error Deleting Image');
    }
    
    public function get(){
        return $this->successResponse('',$this->defaultCameraData());
    }
    
    public function photos(){
        
        return $this->successResponse('',$this->loadPhotos());
    }
    
    public function takePhoto(){
        
        if(!$this->hasPermission('camera.photo.capture',auth()->user())){
            return $this->permissionDenied();
        }
        
        Broadcaster::broadcast(BroadcastChannels::CamExecuting, new BroadcastMessage('','','','','',(object)['executing'=>True,'msg'=>'Capturing Photo...']));
        
        $this->update(null,1);
        
        $res = Python::getInstance()->cameraTasks('photo');
        
        if($res !== null){
            $this->update(1,0);
            $res = $this->successResponse('',(object)['on'=>1,'snap'=>0,'photo'=>$res->name,'path'=>$res->path]);
            Broadcaster::broadcast(BroadcastChannels::CamUpdate, new BroadcastMessage('','','','','',$res));
        }else{
        
            $this->update(null,0);

            Broadcaster::broadcast(BroadcastChannels::CamUpdate, new BroadcastMessage('','','','','',
                $this->failResponse('Unable to take photo','<h3>Camera Error</h3>')));
        }
    }
    
    public function turnOn(){
        $res = Python::getInstance()->cameraTasks('on');
        
        Broadcaster::broadcast(BroadcastChannels::CamExecuting, new BroadcastMessage('','','','','',(object)['executing'=>True,'msg'=>'Powering On...']));
        
        if($res !== null){
            $this->update(1,0);
            $res = $this->successResponse('',(object)['on'=>1,'snap'=>0,'name'=>'','path'=>'']);
            Broadcaster::broadcast(BroadcastChannels::CamUpdate, new BroadcastMessage('','','','','',$res));
        }else{
            Broadcaster::broadcast(BroadcastChannels::CamUpdate, new BroadcastMessage('','','','','',
                $this->failResponse('Unable to turn camera on','<h3>Camera Error</h3>')));
        }
    }
    
    public function turnOff(){
        $res = Python::getInstance()->cameraTasks('off');
        
        Broadcaster::broadcast(BroadcastChannels::CamExecuting, new BroadcastMessage('','','','','',(object)['executing'=>True,'msg'=>'Powering Off...']));
        
        if($res !== null){
            $this->update(0,0);
            $res = $this->successResponse('',(object)['on'=>0,'snap'=>0,'name'=>'','path'=>'']);
            Broadcaster::broadcast(BroadcastChannels::CamUpdate, new BroadcastMessage('','','','','',$res));
        }else{
            Broadcaster::broadcast(BroadcastChannels::CamUpdate, new BroadcastMessage('','','','','',
                $this->failResponse('Unable to turn camera off','<h3>Camera Error</h3>')));
        }
    }
    
    protected function defaultCameraData(){
        return (object)['on'=>$this->cam->on,'snap'=>$this->cam->photo,'name'=>'','path'=>''];
    }
    
    protected function loadPhotos(){
        $files = scandir('/var/www/iot/public/campics',SCANDIR_SORT_ASCENDING);
        $photos = [];
        foreach ($files as $file){
            
            if($file == '.' || $file=='..'){
                continue;
            }
            
            $photos[] = (object)[
               'name'=> substr($file, strripos($file,'/')),
               'path'=>'/campics/'.substr($file,stripos($file,'/public/'))
            ] ;
        }
        
        return $photos;
    }
    
    protected function update($on,$photo){
        $this->cam->on = $on !== null? $on : $this->cam->on;
        $this->cam->photo = $photo !== null? $photo : $this->cam->photo;
        $this->config->camera = json_encode($this->cam);
        $this->config->save();
    }
    
}
