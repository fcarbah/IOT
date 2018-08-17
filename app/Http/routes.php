<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    //session()->flush();
    \App\Classes\Broadcaster::broadcast(\BroadcastChannels::SystemTempChange, new \App\Classes\BroadcastMessage('Temp Threshold Updated','success'));
    return view('index');
})->middleware('ip.access');

Route::post('login','SessionController@login')->middleware('account.policy');

Route::group(['prefix'=>'local'],function(){

    Route::post('camera/photo','LocalController@photoComplete');

    Route::post('camera/off','LocalController@cameraOn');

    Route::post('camera/on','LocalController@cameraOn');
    
    Route::post('driver/presence','LocalController@driverPresence');
    
    Route::post('location','LocalController@location');
    
    Route::post('presence','LocalController@updatePresence');

    Route::post('temperature/new','LocalController@addTemperature');

});

Route::group(['prefix'=>'mobile/api','middleware'=>'mobile.api'],function(){

    Route::group(['prefix'=>'camera'],function(){

        Route::get('/','MobApiController@getCamera');

        Route::get('off','MobApiController@turnCameraOff');

        Route::get('on','MobApiController@turnCameraOn');

        Route::get('photo','MobApiController@takePhoto');

        Route::get('photos','MobApiController@photos');

    });

    Route::get('alarm/messages','MobApiController@getMessages');

    Route::get('dashboard','MobApiController@index');

    Route::get('messages','MobApiController@message');

    Route::post('connect',function(){
        $res = \App\Classes\AppResponse::make('Successful', 'success', false, 1,'');
        return response()->json($res);
    });

});

Route::group(['middleware' => 'auth'], function () {

    Route::get('checkAuthentication','SessionController@checkAuthentication');

    Route::get('dashboard','DashboardController@index');

    Route::get('device','ConfigController@getDeviceInfo');

    Route::get('logout','SessionController@logout');
    
    Route::get('setup','ConfigController@getSetup');

    Route::get('system/logs','ConfigController@logs');
    
    Route::post('setup','ConfigController@saveSetup');

    Route::group(['prefix'=>'alarm'],function(){

        Route::get('messages','AlarmsController@getMessages');

    });

    Route::group(['prefix'=>'camera'],function(){

        Route::get('/','CameraController@index');

        Route::get('off','CameraController@turnOff');

        Route::get('on','CameraController@turnOn');

        Route::get('photo','CameraController@takePhoto');

        Route::get('photos','CameraController@photos');

        Route::post('photos/delete','CameraController@deletePhoto');

    });

    Route::group(['prefix'=>'config'],function(){

        Route::get('contacts','ContactsController@getContacts');

        Route::get('network','ConfigController@getNetworkConfig');

        Route::get('notification','ConfigController@getNotifConfig');

        Route::get('security','ConfigController@getSecurityConfig');

        Route::get('temperature','ConfigController@getTempConfig');

        Route::get('wireless','WirelessController@getNetworks');

        Route::get('wireless/config','ConfigController@getWirelessConfig');


        Route::post('contacts/add','ContactsController@addContact');

        Route::post('contacts/delete/{id}','ContactsController@deleteContact');

        Route::post('contacts/edit/{id}','ContactsController@updateContact');

        Route::post('network','ConfigController@saveNetworkConfig');

        Route::post('notification','ConfigController@saveNotifConfig');

        Route::post('security','ConfigController@saveSecurityConfig');

        Route::post('temperature','ConfigController@saveTempConfig');

        Route::post('wireless/add','WirelessController@addNetwork');

        Route::post('wireless/changepassword/{id}','WirelessController@changePassword');

        Route::post('wireless/config','ConfigController@saveWirelessConfig');

        Route::post('wireless/delete/{id}','WirelessController@deleteNetwork');

        Route::post('wireless/edit/{id}','WirelessController@updateNetwork');

    });

    Route::group(['prefix'=>'mobile'],function(){

        Route::get('keys','UserController@getMobileKeys');

        Route::post('keys/add','UserController@createMobileKey');

        Route::post('keys/delete/{id}','UserController@deleteMobileKey');

        Route::post('keys/refresh/{id}','UserController@refreshMobileKey');

    });

    Route::group(['prefix'=>'security'],function(){

        Route::get('acl','SecurityController@getIpFilters');

        Route::get('ap','SecurityController@getPolicies');


        Route::post('acl/add','SecurityController@addIpFilter');

        Route::post('acl/delete/{id}','SecurityController@deleteIpFilter');

        Route::post('acl/edit/{id}','SecurityController@editIpFilter');

        Route::post('ap/edit/{id}','SecurityController@editPolicy');

    });

    Route::group(['prefix'=>'users'],function(){

        Route::get('/','UserController@getUsers');


        Route::post('add','UserController@createUser');

        Route::post('changepassword/{id}','UserController@changePassword');

        Route::post('delete/{id}','UserController@deleteUser');

        Route::post('edit/{id}','UserController@editUser');

        Route::post('resetpassword/{id}','UserController@resetPassword');

        Route::post('suspend/{id}','UserController@suspendUser');

    });

});
