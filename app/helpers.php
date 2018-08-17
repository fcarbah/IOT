<?php

function accessModes(){
    return [
        (object)['id'=>'Allow','value'=>'Allow'],
        (object)['id'=>'Deny','value'=>'Deny']
    ];
}

function alertIntervals(){
    return (object)[
        'contacts'=>[
            (object)['id'=>1,'value'=>'Every  Minute'],
            (object)['id'=>2,'value'=>'Every 2 Minutes'],
            (object)['id'=>5,'value'=>'Every 5 Minutes']
        ],
        'emergency'=>[
            (object)['id'=>10,'value'=>'After 10 Minutes'],
            (object)['id'=>15,'value'=>'After 15 Minutes']
        ]
    ];
}

function alertTypes(){
    return[
        (object)['id'=>'Call','value'=>'Call'],
        (object)['id'=>'Email','value'=>'Email'],
        (object)['id'=>'SMS','value'=>'SMS']
    ];
}

function arrayTrim(&$array){

    if(is_array($array)){
        foreach($array as $key=>$data){
            $array[$key] = trim($data);
        }
    }else{
        return $array;
    }
}

function contactTypes(){
    return [
        (object)['id'=>'Contacts','value'=>'Contacts'],
        (object)['id'=>'Emergency Contacts','value'=>'Emergency Contacts'],
    ];
}

function dateDiff($dateStr, $dateStr2=null,$diffType=1){

    $date1 = Carbon\Carbon::instance(new DateTime($dateStr));
    $date2 = $dateStr2 == nullOrEmptyString()? Carbon\Carbon::instance(new DateTime()) : Carbon\Carbon::instance(new DateTime($dateStr2));

    switch($diffType){
        case 1:
        default:
            return $date2->diffInMinutes($date1);
        case 2:
            return $date2->diffInHours($date1);
        case 3:
            return $date2->diffInDays($date1);
    }
}

function decryptVal($value){
    $original = substr($value,3,strlen($value)-6);
    return base64_decode($original);
}

function decryptArray($array){

    $res =[];

    if(is_array($array)){

        foreach($array as $value){
            $res[] = decrypt($value);
        }
    }

    return $res;
}

function encryptVal($value){
    return generateRandomChars().base64_encode($value).generateRandomChars();
}

function encryptArray($array){

    $res =[];

    if(is_array($array)){

        foreach($array as $value){
            $res[] = encrypt($value);
        }
    }

    return $res;
}

function eventTypes(){
    return \Cache::remember('events',500,function(){
        return \App\Models\EventTypes::all();
    });
}

function formatDate($type=1,$datestring=null){
        if($datestring instanceof DateTime){
            $date = $datestring;
        }
        else{
            $date = ($datestring == null)? new DateTime() : new DateTime($datestring);
        }

        $date = Carbon\Carbon::instance($date);

    switch($type){
        case 0:
            return $date->format('Y-m-d H:i:s');
        case 1:
        default:
            return $date->diffForHumans();
        case 2:
            return $date->format('m/d/Y');
        case 3:
            return $date->format('m/d/Y @g:i:s A');
        case 4:
            return $date->format('m/d/Y @g:i A');
        case 5:
            return $date->format('m/d/Y @H:i:s');
        case 6:
            return $date->format('M Y');
        case 7:
            return $date->format('M j');
        case 8:
            return $date->format('\@g:i:s A');
        case 9:
            return $date->format('m/d');
        case 10:
            return $date->format('F j, Y');
        case 11:
            return $date->format('m/d @H:i');
        case 12:
            return $date->format('g:i A');
    }

}

function generateRandomChars($length=3){
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789$!@_-()#&';
    $string = '';
    for ($i = 0; $i < 10; $i++) {
        $string .= $characters[rand(0, strlen($characters) - 1)];
    }
    return substr(base64_encode($string),0,$length);
}

function getDateString($dt = null,$type=0){

    if($dt instanceof DateTime){
        $date = $dt;
    }else{
        $date = $dt == null? new DateTime() : new DateTime($dt);
    }

    if($type==0){
        return $date->format('Y-m-d H:i:s');
    }

    return $date->format('Y-m-d');
}

function ipInRange($ip,$startIp,$endIp){
    $currentIp = ip2long($ip);
    $start = ip2long($startIp);
    $end = ip2long($endIp);

    return $currentIp >= $start && $currentIp <= $end;
}

/*function isIpInRange($ip,$startIp,$octet){
    $startOctets = explode('.',$startIp);
    $regex = "/";

    foreach($startOctets as $key=>$oct){

        if($key===$octet){
            $regex .= '.*';
            break;
        }else{
            $regex .= $oct.'\.';
        }
    }
    $regex.='/';
    return preg_match($regex, $ip);

}*/

/*function isIpWithinRange($ip,$startIp,$endIp,$octet){

    $ipOctets = explode('.',$ip);
    $startOctets = explode('.',$startIp);
    $endOctets = explode('.',$endIp);


    for($i=$octet;$i< count($startOctets);$i++){
        $start = intval($startOctets[$i]);
        $end = intval($endOctets[$i]);
        $current = intval($ipOctets[$i]);

        if($current >= $start && $current <= $end){
            continue;
        }else{
            return false;
        }

    }

    return true;

}*/

function isValidIpRange($startIp,$endIp, &$chkOn){

    return ip2long($startIp) <= ip2long($endIp);

    /*$start = explode('.',$startIp);
    $end = explode('.',$endIp);

    $chkOn=1;
    $chkChange = false;

    foreach($start as $key=>$octet){
        if($key==0 && $octet> $end[$key]){
            return false;
        }
        elseif($key==0 && $octet == $end[$key]){
            if(!$chkChange)$chkOn =2;
           continue;
        }else if($key==0){
            $chkChange = true;
            continue;
        }
        if($key==1 && $chkOn==2 && $octet> $end[$key]){
            return false;
        }
        else if($key==1 && $octet== $end[$key]){
            if(!$chkChange)$chkOn = 3;
            continue;
        }
        else if($key==1){
            $chkChange = true;
            continue;
        }
        if($key==2 && $chkOn==3 && $octet> $end[$key]){
            return false;
        }
        else if($key==2 && $octet== $end[$key]){
            if(!$chkChange)$chkOn = 4;
            continue;
        }
        else if($key==2){
            $chkChange = true;
            continue;
        }
        if($key==3 && $chkOn==4 && $octet> $end[$key]){
            return false;
        }
        else if($key==3 && $octet <= $end[$key]){
            return true;
        }

    }

    return true;*/

}

function lockoutDurations(){
    return [
        (object)['id'=>1,'value'=>'1 Minute'],
        (object)['id'=>2,'value'=>'2 Minutes'],
        (object)['id'=>5,'value'=>'5 Minutes'],
        (object)['id'=>10,'value'=>'10 Minutes'],
    ];
}

function networkModes(){
    return [
        (object)['id'=>1, 'value'=>'Dynamic IP Configuration (DHCP)'],
        (object)['id'=>2, 'value'=>'Static IP Configuration']
    ];
}

function resetDurations(){
    return [
        (object)['id'=>10,'value'=>'10 Minutes'],
        (object)['id'=>15,'value'=>'15 Minutes'],
        (object)['id'=>20,'value'=>'20 Minutes'],
        (object)['id'=>25,'value'=>'25 Minutes'],
        (object)['id'=>30,'value'=>'30 Minutes']
    ];
}

function regexReplace($input, $pattern='/\W+/',$replacement='_' ){
   return preg_replace($pattern,$replacement,$input);
}

function regexReplaceArray(&$input, $pattern='/\W+/',$replacement='_' ){

    foreach($input as $key=>$val){
        if(is_numeric($val)){
            continue;
        }
        $input[$key] = regexReplace($val,$pattern,$replacement);
    }
}

function stringReplace($input,$replace,$replacement='_' ){
    return str_replace($replace,$replacement,$input);
}

function wirelessAuthModes(){
    return [
        (object)['id'=>WirelessAuthenticationTypes::Open,'value'=> WirelessAuthenticationTypes::Open],
        (object)['id'=>WirelessAuthenticationTypes::MAC,'value'=> WirelessAuthenticationTypes::MAC],
        (object)['id'=>WirelessAuthenticationTypes::WEP,'value'=> WirelessAuthenticationTypes::WEP],
        (object)['id'=>WirelessAuthenticationTypes::WPA2PSK,'value'=> WirelessAuthenticationTypes::WPA2PSK],
        (object)['id'=>WirelessAuthenticationTypes::WPA2ENT,'value'=> WirelessAuthenticationTypes::WPA2ENT]
    ];
}
