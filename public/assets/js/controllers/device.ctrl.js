(function () {
    'use strict';

    angular

    .module('ctrl.device', ['services'])

    .controller('InfoCtrl', ['$scope','HttpService','AlertService','MapService','WSService', function ($scope,HttpService,AlertService,MapService,WSService) {

        $scope.device={name:'Raspberry Pi',model:'B',os:'Linux v4.2',platform:'WebOS',softwareVersion:'1.01',
            hardware:{ram:'2G',hdd:'32G',free:'28G', used:'4G',cpu:'Intel I7',clock:'3.2GHz',cores:4},
            environment:{temp:30,humidity:50,presence:true},
            location:{lat:41.8240,long:-71.4128},
            alarm:{status:true,time:'01/06/2017 @7:57pm',duration:'5mins'}
        };

        $scope.system={init:false};
        $scope.environment={init:false};
        $scope.location={init:false};
        $scope.alarm ={init:false, status:false};

        $scope.loadInfo = function(){
            HttpService.get('/device',$scope.setInfo);
        };
        $scope.setInfo = function(res){
            if(!res.error){
                $scope.system = res.data.sysinfo;
                $scope.environment = res.data.env;
                $scope.alarm = res.data.alarm;
                $scope.location=res.data.location;
                MapService.drawCurrent($scope.location.lat,$scope.location.lon);
            }
        };
        $scope.checkForUpdates = function(){

        };

        $scope.utilization = function(param,prefix){
            if(prefix===undefined){
                prefix = '';
            }
            if(param >= 0 && param <= 40)
                return prefix+'success';
            if(param >40 && param <=60)
                return prefix+'info';
            if(param >60 && param <=85 )
                return prefix+'warning';

            return prefix+'danger';
        };


        $scope.wsAlarmDetail = function(res){
            $scope.alarm = res.data.alarm;
            $scope.setEnv(res.data);
        };
        $scope.wsDAlarmOff = function(res){
            $scope.alarm = res.data.alarm;
        };
        $scope.wsPresenceUpdate = function(res){
            $scope.environment.presence = parseInt(res.data);
        };
        $scope.wsTempUpdate = function(res){
            $scope.setEnv(res.data);
        };
        $scope.wsLocUpdate = function(res){
            $scope.location = res.data;
            $scope.location.lastUpdate = res.data.lastUpdate;
            MapService.drawCurrent($scope.location.lat,$scope.location.lon);
        };

        $scope.setEnv = function(data){
            $scope.environment.temp = parseInt(data.temp.temp);
            $scope.environment.humidity = parseInt(data.temp.humidity);
            $scope.environment.presence = parseInt(data.temp.presence);
            $scope.environment.created_at = data.temp.created_at;
        };

        WSService.subscribe('alarm_details',$scope.wsAlarmDetail);
        WSService.subscribe('alarm_off',$scope.wsDAlarmOff);
        WSService.subscribe('presence_update',$scope.wsPresenceUpdate);
        WSService.subscribe('temp_update',$scope.wsTempUpdate);
        WSService.subscribe('loc_update',$scope.wsLocUpdate);

        $scope.loadInfo();

    }])

    .controller('LogCtrl', ['$scope','ngTableParams', '$filter','HttpService','AlertService', function ($scope,ngTableParams,$filter,HttpService,AlertService) {

        $scope.accessLogs=[];

        $scope.accessTable = new ngTableParams({
            page: 1,            // show first page
            count: 10,          // count per page
            sorting: {
                created_at: 'desc'     // initial sorting
            }
        }, {
            //groupBy: 'eventType.name',
            total: $scope.accessLogs.length, // length of data
            getData: function($defer, params) {
                    // use build-in angular filter
                    var orderedData = params.sorting() ? $filter('orderBy')($scope.accessLogs, params.orderBy()) : $scope.accessLogs;

                    $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
            }
        });

        $scope.loadLogs = function(){
            HttpService.get('/system/logs',$scope.setLogs);
        };
        $scope.setLogs = function(res){
            if(!res.error){
                $scope.accessLogs = res.data.accessLogs;
                $scope.accessTable.reload();
            }
        };


        $scope.loadLogs();

    }]);

})();
