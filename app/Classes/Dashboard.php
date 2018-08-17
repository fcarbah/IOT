<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes;

use App\Models\Temperatures;
use App\Models\Alarms;
use App\Models\SysConfig;
use \Carbon\Carbon;
/**
 * Description of Dashboard
 *
 * @author FMCJr
 */
class Dashboard {
    
    use \App\Traits\ResponseTrait;
    
    private static $self;
    private $mobile = false;
    
    private function __construct($mobile=false) {
        $this->mobile = $mobile;
        self::$self = $this;
    }
    
    public static function getInstance($mobile=false){
        if(self::$self == null){
            self::$self = new Dashboard($mobile);
        }
        
        return self::$self;
    }
    
    public function get(){
        return $this->buildResponse('', 'success', FALSE, [
            'alarm'=>$this->getAlarm(),
            'chart'=>$this->getChartData(),
            'env'=>$this->getEnvironment(),
            'location'=>$this->getLocation(),
        ]);
    }
    
    public function getAlarm($dataOnly=true){
       
        $alarm = Alarms::last();
        
        if($alarm != null){
            $alarm->duration = formatDate(1,$alarm->created_at);
        }
        
        if($dataOnly){
            return $alarm;
        }
        
        return $this->buildResponse('', 'success', FALSE, $alarm);
    }
    
    public function getChartData($dataOnly=true){

        $temps = Temperatures::where('created_at','>=',Carbon::now()->subMinutes(10))
            ->where('created_at','<=',Carbon::now())
            ->get();

        $col = ['x'];
        $d1 = ['Upper Threshold'];
        $d2 = ['Temperature'];
        $d3 = ['Lower Threshold'];
        
        
        foreach($temps as $temp){
            $col[] = (new \DateTime($temp->created_at))->format('Y-m-d H:i:s');
            $d1[] = $temp->upper;
            $d2[] = $temp->temp;
            $d3[] = $temp->lower;
        }
        
        $data = [$col,$d1,$d2,$d3];
        
        $colors = ['"Upper Threshold"'=>'#F44336','"Temperature"'=>'#191547','"Lower Threshold"'=>'#65C9BF'];
        
        $chart = new \App\Charts\C3();
        $chart->data['columns'] = $data;
        $chart->data['colors'] = $colors;

        if($dataOnly){
            return $chart;
        }
        return $this->buildResponse('', 'success', FALSE, $chart);   
    }
    
    public function  chartData2($dataOnly=true){
        $temps = Temperatures::where('created_at','>=',Carbon::now()->subMinutes(10))
            ->where('created_at','<=',Carbon::now())
            ->get();
        $thresholds = json_decode(SysConfig::first()->temperature);
        
        $labels = [];
        
        $upperds =['label'=>'Upper Threshold','backgroundColor'=>'#F44336','data'=>[],'fill'=>false];
        $lowerds=['label'=>'Lower Threshold','backgroundColor'=>'#65C9BF','data'=>[],'fill'=>false];
        $tempds=['label'=>'Temperature','backgroundColor'=>'#191547','data'=>[],'fill'=>false];
        
        foreach($temps as $temp){
           $labels[] = (new \DateTime($temp->created_at))->format('Y-m-d H:i:s'); 
           $upperds['data'][] = $thresholds->upper;
           $tempds['data'][] = $temp->temp;
           $lowerds['data'][] = $thresholds->lower;
        }
        
        $chart = new \App\Charts\LineChart();
        $chart->series = ['Upper Threshold','Temperature','Lower Thresholds'];
        $chart->mData = [$upperds['data'],$tempds['data'],$lowerds['data']];
        $chart->labels = $labels;
        $chart->data['labels'] = $labels;
        $chart->data['datasets'][] = (object)$upperds;
        $chart->data['datasets'][] = (object)$tempds;
        $chart->data['datasets'][] = (object)$lowerds;
        
        if($dataOnly){
            return $chart;
        }
        return $this->buildResponse('', 'success', FALSE, $chart);
        
    }
    
    public function getEnvironment($dataOnly=true){
       
        $env = Temperatures::orderBy('created_at','desc')->first();
        
        if($env != null){
            ///$env->presence=0;
        }
        
        if($dataOnly){
            return $env;
        }
        
        return $this->buildResponse('', 'success', FALSE, $env);
    }
    
    public function getLocation($dataOnly=true){
        $config = \App\Models\DeviceInfo::first();
        $loc = $config->location;
        $location = $loc != null? json_decode($loc) : (object)['lat'=>'','lon'=>'','alt'=>'','speed'=>''];
        $location->lastUpdate = $config->updated_at != null ? getDateString($config->updated_at) : getDateString();
        if($dataOnly){
            return $location;
        }
        return $this->buildResponse('', 'success', FALSE, $location);
    }
    
}
