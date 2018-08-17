<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Classes\BroadcastMessage;

class AutoAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically send notifications to parents if child inside vehicle every 30 minutes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $alarm = \App\Models\Alarms::active();
        
        if($alarm == null){
            //$contacts = \App\Models\Contacts::contacts();
            $lastTemp = \App\Models\Temperatures::last();
            
            $driverInfo = json_decode(\App\Models\DeviceInfo::first()->driver);
            
            if($lastTemp != null && dateDiff($lastTemp->created_at) <= 10 && $lastTemp->presence== 1 && $driverInfo->presence ==0){
                $msg='Child in vehicle';
                \App\Classes\Broadcaster::broadcast(\BroadcastChannels::ClientAlarmNotification,new BroadcastMessage($msg,'','info','Child Alert'));
                \Log::info('Auto Alert');
            }

        }
        
    }
}
