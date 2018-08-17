var socket = io("http://"+host+":4000");

(function () {

    'use strict';

    angular
    .module('ctrl.main', ['ui-notification'])

    .service('AlertService',['Notification',function(Notification){

        var alertService = {} ;

        alertService.showMessage = function(message,title,type,delay){
            type = type===undefined? 'primary': type;
            delay = delay===undefined? 10000: delay;
            title= title===undefined? 'Notification': title;

            if(type==='success')
            Notification.success({ message: message, title: title, delay: delay,type:type }, 'blocked' );

            else if(type==='info')
            Notification.info({ message: message, title: title, delay: delay,type:type }, 'blocked' );

            else if(type==='warning')
            Notification.warning({ message: message, title: title, delay: delay,type:type }, 'blocked' );

            else if(type==='danger')
            Notification.error({ message: message, title: title, delay: delay,type:type }, 'blocked' );

            else
            Notification.custom({ message: message, title: title, delay: delay,type:type }, 'blocked' );

        };
        alertService.show = function(res){
           var message = res.messages.length > 0? res.messages.join('<br/>') : '';

           if(message !== ''){
               this.showMessage(message,res.title,res.type);
           }
        };

        return alertService;

    }])

    .service('WSService',['Socket','BroadcastService','AlertService',function(Socket,BroadcastService,AlertService){

        var service = {};

        service.sessions =[];

        service.channels =[];

        service.active = null;

        service.available = true;

        service.counts = {};

        service.setSessions = function(sessions){

            this.sessions = sessions;

            _.each(this.sessions,function(session,key){
                if(session !== service.active){
                    service.counts[session] = 0;
                    Socket.on(session,service.handleMessage);
                }
            });

        };

        service.setActiveSession = function(session){
            this.active = session;
            var idx = this.sessions.indexOf(session);

            if(idx >= 0){
                this.sessions.splice(idx,1);
                delete this.counts[session];
            }

            Socket.remove(session);
        };

        service.handleMessage = function(message){
            //var displayMsg = message.message;
            AlertService.showMessage(message.message,'',message.alert);
            //BroadcastService.broadcast('chatnotification');
        };

        service.subscribeSelf = function(user){
            Socket.on(user.toLowerCase(),this.handleSelfSubscribe);
        };

        service.handleSelfSubscribe = function(message){
            if(service.available){
                service.subscribe(message.channel);
            }
        };

        service.subscribe = function(channel,callback){
            if(service.sessions.indexOf(channel) <0 && service.active !== channel){
                service.sessions.push(channel);
                service.counts[channel] = 0;
                if(callback === undefined){
                    Socket.on(channel,service.handleMessage);
                }
                else{
                    Socket.on(channel,callback)
                }
                service.saveToStorage(channel,callback);
            }
        };

        service.unsubscribeAll = function(){
            _.each(service.sessions,function(session){
               Socket.remove(session);
            });
        };

        service.disconnect = function(){
          Socket.disconnect();
        };
        service.refresh = function(){
            if(Socket.init()){
                service.retrieveFromStorage();
                console.log(service.channels)
                for(var i in service.channels){
                    var ch = service.channels[i];
                    service.subscribe(ch.channel,ch.callback);
                }
            }
        };
        service.clearStorage = function(){
            sessionStorage.removeItem('wsChannels');
        }
        service.saveToStorage = function(channel,callback){
            service.retrieveFromStorage();
            service.channels.push({'channel':channel,'callback':callback});
            sessionStorage.setItem('wsChannels',JSON.stringify(service.channels));
        };
        service.retrieveFromStorage = function(){
            var data = sessionStorage.getItem('wsChannels');

            if(data != undefined || data != null){
                service.channels = JSON.parse(data);
            }
        }

        return service;

    }])


    .controller('MainCtrl', ['$scope', '$rootScope', '$timeout','$interval', 'cfpLoadingBar', '$state','HttpService','WSService','BroadcastService','AlertService',
        function ($scope, $rootScope, $timeout,$interval, cfpLoadingBar, $state,HttpService,WSService,BroadcastService,AlertService) {

        WSService.clearStorage();
        WSService.refresh();

        $rootScope.$on('httpError',function(){
            var res = BroadcastService.data;
            BroadcastService.data=null;

            if(res !== null && res.error !== undefined){
                AlertService.show(res);
                if(res.redirectUrl !==''){
                    window.location = res.redirectUrl;
                    return;
                }
                if(res.redirectState !==''){
                    $state.go(res.redirectState);
                    return;
                }
            }else{
                WSService.disconnect();
                $rootScope.handleLogout({error:false});
            }
        });

        $interval(function(){
            WSService.refresh();
        },60000);

        //subscribe to channels
        $scope.wsAlarmMsg = function(res){
            $scope.recentMessages.pop();
            $scope.recentMessages.unshift(res.data);
        };
        $scope.wsAlarmOff = function(res){
            AlertService.showMessage(res.message,'ALARM OFF',res.alert);
        };
        $scope.wsAlarm = function(res){
            AlertService.showMessage(res.message,res.title,res.alert);
        };

        WSService.subscribe('alarm',$scope.waAlarm);
        WSService.subscribe('alarm_off_msg',$scope.waAlarmOff);
        WSService.subscribe('alarm_msg',$scope.wsAlarmMsg);


        //set recent alarm messages
        $scope.recentMessages = [];
        $scope.loadRecentMessages = function(){
            HttpService.get('/alarm/messages?recent=true&limit=5',$scope.setRecentMessages);
        };
        $scope.setRecentMessages = function(res){
            if(!res.error){
                $scope.recentMessages = res.data;
            }
        };
        $scope.loadRecentMessages();
        //end alarm recent messages

        $scope.scrollbarConfig = {
                autoResize: true,
                scrollbar: {
                        width: 2,
                        hoverWidth: 2,
                        color: '#65cac0',
                        show: false
                },
                scrollbarContainer: {
                        width: 4,
                        color: '#e9f0f5'
                },
                scrollTo: null
        };

        $scope.tags = ['sample tag #1','sample tag #2'];

        $scope.scrollbarConfigInner = {
                autoResize: true,
                scrollbar: {
                        width: 4,
                        hoverWidth: 2,
                        color: '#65cac0',
                        show: false
                },
                scrollbarContainer: {
                        width: 8,
                        color: '#e9f0f5'
                },
                scrollTo: null
        };

        // router changed state
        $rootScope.$on('$stateChangeSuccess', function (event, data) {
                $timeout(function () {
                        $scope.show_page = true;
                        cfpLoadingBar.complete();
                }, 500);
        });

        $rootScope.$on('$stateChangeStart', function (event, data) {
                window.scrollTo(0,0);
                $scope.show_page = false;
                cfpLoadingBar.start();
        });

    }]);
})();
