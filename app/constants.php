<?php

interface AccountPolicyCodes{
    const LockedOut = 1;
    const LockedOutReach = 2;
    const ClearLockedOut=3;
    const ThresholdReached=4;
    const ClearThreshold=5;
    const IncrementThreshold=6;
    const IncremenAttempts=7;
    const ClearAttempts=8;
    const AttemptsWait =9;
    const LockedWait=10;
    const Success=11;
    const LockoutKeyAbbr = 'ap_lockout_';
    const ThresholdKeyAbbr = 'ap_threshold_';
}

interface BroadcastChannels{
    const ClientAlarmNotification = 'alarm';
    const ClientAlarmData = 'alarm_details';
    const ClientAlarmMsg = 'alarm_msg';
    const ClientAlarmOff ='alarm_off';
    const ClientAlarmOffNotif ='alarm_off_msg';
    const SystemTempChange ='sys_temp_upd';
    const SystemAlarmOn = 'sys_alarm_on';
    const SystemAlarmOff = 'sys_alarm_off';
    const SystemPresence = 'sys_presence_upd';
    const TempUpdate = 'temp_update';
    const PresenceUpdate = 'presence_update';
    const LocationUpdate = 'loc_update';
    const CamUpdate = 'cam_update';
    const CamExecuting = 'cam_executing';
}

interface Roles{
    const Admin=1;
    const PowerUser = 2;
    const User = 3;
}

interface EventType{
    const LoginFail =1;
    const LoginSucces =2;
    const AccountLocked =3;
    const UserOnline=4;
    const UserOffline=5;
    const UserAdd =6;
    const UserEdit =7;
    const UserDelete =8;
    const WirelessAdd=9;
    const WirelessEdit=10;
    const WirelessDelete =11;
    const WirelessUpdate =12;
    const NetworkUpdate =13;
    const IPUpdate=14;
    const AddFilter=15;
    const EditFilter=16;
    const DeleteFilter=17;
    const SecurityUpdate=18;
    const UpdateAP=19;
    const AddContact=20;
    const EditContact=21;
    const DeleteContact=22;
    const TempUpdate=23;
    const NotificationUpdate=24;
    const DeviceUpdate=25;
    const DeviceRollback=26;
    const Logout=27;
    const AlarmRaised =28;
    const AlarmCleared = 29;
    const AlarmOverride = 30;
}

interface AlertTypes{
    const SMS = 'SMS';
    const Call = 'Call';
    const Email = 'Email';
}

interface ContactTypes{
    const Emergency='Emergency Contacts';
    const MyContacts='Contacts';
}

interface AccessTypes{
    const Allow= 'Allow';
    const Deny='Deny';
}

interface WirelessAuthenticationTypes{

    const Open = 'Open (No Encryption)';
    const MAC = 'MAC-based access control (No Encryption)';
    const WEP = 'WEP (Shared Network Key)';
    const WPA2PSK = 'WPA2-PSK (Shared Network Key)';
    const WPA2ENT = 'WPA2-Enterprise with 802.1x authentication';
}
