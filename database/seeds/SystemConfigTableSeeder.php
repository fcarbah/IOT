<?php

use Illuminate\Database\Seeder;

class SystemConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tempConfig = \App\Classes\SystemConfig::tempConfig();
        $tempConfig->lower= 50;
        $tempConfig->upper = 75;
        
        $deftempConfig = \App\Classes\SystemConfig::defTempConfig();
        $deftempConfig->lower=69;
        $deftempConfig->upper = 70;
        $deftempConfig->min=40;
        $deftempConfig->max=95;
        
        $securityConfig = App\Classes\SystemConfig::securityConfig();
        $notifConfig = App\Classes\SystemConfig::notificationConfig();
        $wirelessConfig = App\Classes\SystemConfig::wirelessConfig();
        $netConfig = App\Classes\SystemConfig::networkConfig();
        
        \App\Models\SysConfig::create([        
            'wireless'=>json_encode($wirelessConfig),
            'network'=>json_encode($netConfig),
            'security'=>json_encode($securityConfig),
            'defTemperature'=>json_encode($deftempConfig),
            'temperature'=>json_encode($tempConfig),
            'notification'=>json_encode($notifConfig),
            'createdBy'=>1,
            'updatedBy'=>1
        ]);
    }
    
}
