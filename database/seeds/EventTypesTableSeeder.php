<?php

use Illuminate\Database\Seeder;

class EventTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name'=>'Unknown','code'=>0,'description'=>'Unknown'],
            ['name'=>'Failed_Login','code'=>1,'description'=>'Login Failed'],
            ['name'=>'Pass_Login','code'=>2,'description'=>'Login Succesful'],
            ['name'=>'Account_Locked','code'=>3,'description'=>'Account Locked'],
            ['name'=>'User_Online','code'=>4,'description'=>'User Online'],
            ['name'=>'User_Offline','code'=>5,'description'=>'User Online'],
            ['name'=>'User_Add','code'=>6,'description'=>'User Account Created'],
            ['name'=>'User_Edit','code'=>7,'description'=>'User Account Updated'],
            ['name'=>'User_Delete','code'=>8,'description'=>'User Account Deleted'],
            ['name'=>'Wireless_Add','code'=>9,'description'=>'Wireless network Added'],
            ['name'=>'Wireless_Edit','code'=>10,'description'=>'Wireles Network Updated'],
            ['name'=>'Wireless_Delete','code'=>11,'description'=>'Wireless Network Deleted'],
            ['name'=>'Wireless_Update','code'=>12,'description'=>'Wireless Settings Updated'],
            ['name'=>'Network_Update','code'=>13,'description'=>'Network Settings Updated'],
            ['name'=>'IP_Update','code'=>14,'description'=>'IP Address Updated'],
            ['name'=>'Filter_Add','code'=>15,'description'=>'ACL Rule Added'],
            ['name'=>'Filter_Edit','code'=>16,'description'=>'ACL Rule Updated'],
            ['name'=>'Filter_Delete','code'=>17,'description'=>'ACL Rule deleted'],
            ['name'=>'Security_Update','code'=>18,'description'=>'Security Settings Updated'],
            ['name'=>'AP_Update','code'=>19,'description'=>'Account Policy Updated'],
            ['name'=>'Contact_Add','code'=>20,'description'=>'Contact Added'],
            ['name'=>'Contact_Edit','code'=>21,'description'=>'Contact Updated'],
            ['name'=>'Contact_Delete','code'=>22,'description'=>'Contact Deleted'],
            ['name'=>'Temperature_Update','code'=>23,'description'=>'User Online'],
            ['name'=>'AlertInterval_Update','code'=>24,'description'=>'Alert Interval updated'],
            ['name'=>'Device_Update','code'=>25,'description'=>'Device software updated'],
            ['name'=>'Device_Rollback','code'=>26,'description'=>'Device software rollback'],
            ['name'=>'User_Logout','code'=>27,'description'=>'User Logout'],
            ['name'=>'Alarm_Raised','code'=>28,'description'=>'Alarm Raised. Temperature Level Equal or Over Threshold'],
            ['name'=>'Alarm_Cleared','code'=>29,'description'=>'Alarm Cleared. Temperature Level Normal'],
            ['name'=>'Alarm_Override','code'=>30,'description'=>'Alarm Cleared. Physical switch pressed'],
        ];
        
        foreach($data as $type){
            \App\Models\EventTypes::create($type);
        }
    }
}
