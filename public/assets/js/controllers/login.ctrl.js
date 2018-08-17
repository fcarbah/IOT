(function () {
    'use strict';

    angular
    
    .module('ctrl.login', ['ngMessages','ui-notification'])
    
    .controller('LoginCtrl',['$rootScope','$scope','$timeout','cfpLoadingBar','HttpService','AuthService','Notification',
    function($rootScope,$scope,$timeout,cfpLoadingBar,HttpService,AuthService,Notification){
            
        $scope.credentials = {username:'',password:''};
        
        $scope.login = function(){
            HttpService.post('/login',$scope.credentials,{},$scope.handleLogin);
            cfpLoadingBar.start();
        };
        
        $scope.handleLogin = function(response){
            $rootScope.state = 'app.admin.dashboard';
            $scope.show(response);
            if(!response.error){
                cfpLoadingBar.complete();
                AuthService.checkAuthentication();
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
            
    }]);
    
})();

