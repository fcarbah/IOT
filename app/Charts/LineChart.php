<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Charts;

/**
 * Description of LineChart
 *
 * @author FMCJr
 */
class LineChart {
    public $type = 'line';
    
    public $data = [
        'labels'=>[],
        'datasets'=>[],    
    ];
    public $options = [
        'scales'=>[
            'xAxes'=>[[
                'type'=>'time','gridLines'=>['lineWidth'=>0,'color'=>'#fff'],
                'scaleLabel'=>[],'time'=>['displayFormats'=>['hour'=>'h:']]
            ]],
            'yAxes'=>[['type'=>'linear','gridLines'=>['color'=>'#eee']]]
        ]
    ];
    
    public $mData=[];
    public $series =[];
    public $labels = [];
}
