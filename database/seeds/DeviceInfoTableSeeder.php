<?php

use Illuminate\Database\Seeder;

class DeviceInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultInfo = \App\Classes\SystemConfig::deviceInfoConfig();
        
        App\Models\DeviceInfo::create([
            'camera'=>json_encode($defaultInfo->camera),
            'location'=>json_encode($defaultInfo->location),
            'driver'=>json_encode($defaultInfo->driver)
        ]);
    }
}
