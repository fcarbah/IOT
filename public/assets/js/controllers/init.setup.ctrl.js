(function () {
    'use strict';

    angular
    
    .module('ctrl.setup', ['ngMessages','ui-notification'])

    .controller('SetupCtrl',['$rootScope','$scope','$state','cfpLoadingBar','HttpService','AuthService','Notification','Tools',
    function($rootScope,$scope,$state,cfpLoadingBar,HttpService,AuthService,Notification,Tools){
            
        $scope.availableNotifs ={};
        $scope.defNotif={};
        $scope.newContact={name:'',phone:'',email:''};
        $scope.defTemp={};
        $scope.temp = {};

        $scope.load = function(){
        	HttpService.get('/setup',$scope.setData,$scope.handleError);
        }

        $scope.handleError = function(res){
        	$rootScope.handleLogout({error:false});
        }

        $scope.setData = function(res){

        	if(!res.error){
        		$scope.availableNotifs = res.data.notif.alertIntervals;
                $scope.defNotif = res.data.notif.conf;
                $scope.newContact = res.data.owner;
                $scope.defTemp = res.data.temp.baseTemp;
                $scope.temp = res.data.temp.temp;
        	}
        };

        $scope.notifChange = function(type){
            if(type===1){
                var indx = Tools.findIndex($scope.defNotif.contacts.id,$scope.availableNotifs.contacts,'id');
                $scope.defNotif.contacts = $scope.availableNotifs.contacts[indx];
            }else{
                var indx = Tools.findIndex($scope.defNotif.emergency.id,$scope.availableNotifs.emergency,'id');
                $scope.defNotif.emergency = $scope.availableNotifs.emergency[indx];
            }
            
        };

        $scope.submit = function(){
        	var data = {'temp':$scope.temp,'notif':$scope.defNotif,'owner':$scope.newContact};
        	HttpService.post('/setup',data,{},$scope.handleSubmit,$scope.handleError);
        	cfpLoadingBar.start();
        };

        $scope.handleSubmit = function(response){
        	
            $rootScope.state = 'app.admin.dashboard';
            $scope.show(response);
            if(!response.error){
                cfpLoadingBar.complete();
                AuthService.setupComplete = true;
                AuthService.saveAuthData();
                $state.go('app.admin.dashboard')
            }else{
                cfpLoadingBar.complete();
            }
        };
        $scope.showMessage = function(message,title,type,delay){
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
        $scope.show = function(res){
           var message = res.messages.length > 0? res.messages.join('<br/>') : '';

           if(message !== ''){
               this.showMessage(message,res.title,res.type);
           }
        };

        $scope.load();

    }]);

})();