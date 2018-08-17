<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Charts;

/**
 * Description of C3
 *
 * @author FMCJr
 */
class C3 {
    
    public $data = [
        'x'=>'x',
        'xFormat'=>'%Y-%m-%d %H:%M:%S',
        'columns'=>[
            
        ],
        'type'=>'spline',
        'colors'=>[]
    ];
    
    public $axis =[
        'y'=>['show'=>true],
        'x'=>['show'=>true,
            'type'=>'timeseries',
            'tick'=>['format'=>'%H:%M']
        ]
    ];
    
    public $color =['pattern'=>['#e78988','#118ba9','#209D88']];
    
    public $legend = ['show'=>true];
    
    public $tooltip = ['show'=>true];
    
    
    public function build($labels,$colors,$data){
        
    }
    
}
