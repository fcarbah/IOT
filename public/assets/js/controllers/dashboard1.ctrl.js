

(function () {
    'use strict';

    angular
    .module('ctrl.dashboard', ['services','angular-c3-simple'])

    /*.config(['ChartJsProvider',function(ChartJsProvider){
        ChartJsProvider.setOptions({ colors : [ '#F44336', '#65C9BF', '#191547', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'] });
    }])*/

    .controller('DashboardCtrl', ['$scope','$timeout','HttpService','MapService','WSService','AlertService', function ($scope,$timeout,HttpService,MapService,WSService,AlertService) {

        $scope.location = {init:false};
        $scope.alarm={init:false,status:false};
        $scope.environment={init:false};
        $scope.loadChart =false;

        $scope.tempChart = {
            data: {
                columns: [[]],
                type: 'spline',
                colors: {}
            },
            axis: {
                y: { show: true },
                x: { show: true }
            },
            legend: { show: true },
            tooltip: { show: true }
        };

        $scope.load = function(){
            HttpService.get('/dashboard',$scope.setDashboard);
        };

        $scope.setDashboard = function(res){
            if(!res.error){
                $scope.location = res.data.location;
                $scope.environment = res.data.env !== null? res.data.env : $scope.environment;
                $scope.alarm = res.data.alarm !== null ?res.data.alarm : $scope.alarm;
                $scope.tempChart = res.data.chart;
                MapService.drawCurrent($scope.location.lat,$scope.location.lon);
                $scope.loadChart=true;
            }
        };

        $scope.load();

        $scope.wsAlarmDetail = function(res){
            $scope.loadChart = false;
            $scope.alarm = res.data.alarm;
            $scope.setEnv(res.data);
            $scope.tempChart = res.data.chart;
            /*for(var i in $scope.tempChart.data.columns){
                $scope.reloadChartData(i,res.data);
            }*/
            $timeout(function(){
                $scope.loadChart = true;
            },1000);
        };
        $scope.wsDAlarmOff = function(res){
            $scope.alarm = res.data.alarm;
        };
        $scope.wsPresenceUpdate = function(res){
            $scope.environment.presence = parseInt(res.data);
        };
        $scope.wsTempUpdate = function(res){
            $scope.loadChart = false;
            $scope.setEnv(res.data);
            $scope.tempChart = res.data.chart;
            /*for(var i in $scope.tempChart.data.columns){
                $scope.reloadChartData(i,res.data);
            }*/
            $timeout(function(){
                $scope.loadChart = true;
            },1000);
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

        $scope.reloadChartData = function(i,data){
            var addData;
            if(i==='0')
                addData = data.temp.created_at;
            else if(i==='1')
                addData = parseInt(data.temp.upper);
            else if(i==='2')
                addData = parseInt(data.temp.temp);
            else if (i==='3')
                addData = parseInt(data.temp.lower);
            else
                return;

            if($scope.tempChart.data.columns[i].length >10){
                $scope.tempChart.data.columns[i].splice(1,1);
            }
            $scope.tempChart.data.columns[i].push(addData);

        };
        WSService.subscribe('alarm_off',$scope.wsDAlarmOff);
        WSService.subscribe('alarm_details',$scope.wsAlarmDetail);
        WSService.subscribe('presence_update',$scope.wsPresenceUpdate);
        WSService.subscribe('temp_update',$scope.wsTempUpdate);
        WSService.subscribe('loc_update',$scope.wsLocUpdate);


    }]);

})();
