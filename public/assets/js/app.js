var host = document.location.host.replace(/\:\d+/,'');
console.log(host);

var BrowserDetect = {
        init: function () {
            this.browser = this.searchString(this.dataBrowser) || "Other";
            this.version = this.searchVersion(navigator.userAgent) || this.searchVersion(navigator.appVersion) || "Unknown";
        },
        searchString: function (data) {
            for (var i = 0; i < data.length; i++) {
                var dataString = data[i].string;
                this.versionSearchString = data[i].subString;

                if (dataString.indexOf(data[i].subString) !== -1) {
                    return data[i].identity;
                }
            }
        },
        searchVersion: function (dataString) {
            var index = dataString.indexOf(this.versionSearchString);
            if (index === -1) {
                return;
            }

            var rv = dataString.indexOf("rv:");
            if (this.versionSearchString === "Trident" && rv !== -1) {
                return parseFloat(dataString.substring(rv + 3));
            } else {
                return parseFloat(dataString.substring(index + this.versionSearchString.length + 1));
            }
        },

        dataBrowser: [
            {string: navigator.userAgent, subString: "Edge", identity: "MS Edge"},
            {string: navigator.userAgent, subString: "MSIE", identity: "Explorer"},
            {string: navigator.userAgent, subString: "Trident", identity: "Explorer"},
            {string: navigator.userAgent, subString: "Firefox", identity: "Firefox"},
            {string: navigator.userAgent, subString: "Opera", identity: "Opera"},
            {string: navigator.userAgent, subString: "OPR", identity: "Opera"},

            {string: navigator.userAgent, subString: "Chrome", identity: "Chrome"},
            {string: navigator.userAgent, subString: "Safari", identity: "Safari"}
        ]
    };

    BrowserDetect.init();
    //document.write("You are using <b>" + BrowserDetect.browser + "</b> with version <b>" + BrowserDetect.version + "</b>");

(function () {
    'use strict';

    angular

    .module('myapp', [])


    .factory('BroadcastService',function($rootScope){

        var service ={};
        service.data = null;
        service.broadcast = function(msg){
            $rootScope.$broadcast(msg);
        };

        service.emit = function(msg){
            $rootScope.$emit(msg);
        };

        return service;

    })


    .service('HttpService',['$rootScope','$http','$q','BroadcastService',function($rootScope,$http,$q,BroadcastService){

        var deferred = $q.defer();

        this.get = function(uri,callback,errorHandler){
            $rootScope.uri = uri;
            $http.get(uri).success(function(res){
                if (callback !== undefined && callback !== null){
                  deferred.resolve(callback(res));
                }
                return deferred.promise;
            })
            .error(function(res){
                if($rootScope.uri === '/checkAuthentication'){
                    return;
                }
                if(errorHandler !== undefined){
                    errorHandler(res);
                }else{
                    BroadcastService.data = res;
                    BroadcastService.broadcast('httpError');
                }
            });
        };

        this.post = function(uri,data,headers,callback,errorHandler){
            //data._token = tk;
            $http.post(uri,data,headers).success(function(res){
                if (callback !== undefined && callback !== null){
                  deferred.resolve(callback(res));
                }
                return deferred.promise;
            })
            .error(function(res){
                if($rootScope.uri === '/checkAuthentication'){
                    return;
                }

                if(errorHandler !== undefined){
                    errorHandler(res);
                }else{
                    BroadcastService.data = res;
                    BroadcastService.broadcast('httpError');
                }
            });
        };


    }])

    .service('AuthService',['$rootScope','$state','$timeout','HttpService',function($rootScope,$state,$timeout,HttpService){

        this.user = {};
        this.permissions ={};
        this.isAuthenticated = false;
        this.setupComplete = true;

        var srv = this;

        this.checkAuthentication = function(){
            if(srv.user.id === undefined){
                return HttpService.get('/checkAuthentication',srv.handleResponse);
            }else{
                srv.redirect();
            }
        };

        this.handleResponse = function(res){

            if(!res.error){
                srv.isAuthenticated = true;
                srv.user = res.data.user;
                srv.permissions = res.data.permissions;
                srv.setupComplete = res.data.setupComplete;
                srv.saveAuthData();
                $rootScope.user = srv.user;
                $rootScope.permissions = srv.permissions;

                srv.redirect();

            }
            //return res.error;
        };

        this.redirect = function(){
            if($rootScope.state !== undefined & $rootScope.state !== null){
                var toState = $rootScope.state;
                $rootScope.state = null;
                $timeout(function(){$state.go(toState);},1500);
            }
            /*else{
                $timeout(function(){$state.go('app.admin.dashboard');},1500);
            }*/
        };

        this.resetAuthData = function(){
            srv.user = {};
            srv.permissions ={};
            srv.isAuthenticated = false;
            sessionStorage.clear();
        };

        this.logout = function(){
            HttpService.get('logout',srv.handleLogout);
        };

        this.handleLogout = function(res){
            if(!res.error){
                if(socket !== undefined && socket.connected){
                    socket.disconnect();
                }
                srv.resetAuthData();
                $state.go('home');
            }

        };

        this.hasPermission = function(permissionName){
            var haspermissions = angular.copy($rootScope.permissions);

            if(haspermissions === undefined) return false;

            var hasperm = false;
            var plist = permissionName.split('.');

            if(plist.length <1) return false;

            for(var i in plist){
                if(haspermissions[plist[i]] !== undefined){
                    hasperm = haspermissions[plist[i]];
                    haspermissions = angular.copy(hasperm);
                }else{
                    hasperm = false;
                }
            }

            return hasperm;
        };

        this.hasUserPermission = function(permissionName, otherUser){
            var perm = srv.hasPermission(permissionName);

            if(isNaN(perm)){
                return perm;
            }

            if(perm===0) return false;
            if(perm===1) return true;

            if(perm===2 && $rootScope.user.role_id <= otherUser.role_id){
                return true;
            }

            if(perm===3 && $rootScope.user.id <= otherUser.id){
                return true;
            }

            return false;
        };
        $rootScope.handleLogout = srv.handleLogout;
        $rootScope.hasPermission = srv.hasPermission;
        $rootScope.hasUserPermission = srv.hasUserPermission;

        this.saveAuthData = function(){
            sessionStorage.setItem('authData',JSON.stringify({user:srv.user,permissions:srv.permissions,isAuth:srv.isAuthenticated,setupComplete:srv.setupComplete}));
        };

        this.retrieveAuthData = function(){
            var data = sessionStorage.getItem('authData');

            if(data!==undefined && data !== null){
                var authData = JSON.parse(data);
                srv.isAuthenticated = authData.isAuth;
                srv.user = authData.user;
                srv.permissions = authData.permissions;
                srv.setupComplete = authData.setupComplete;
                $rootScope.user = srv.user;
                $rootScope.permissions = srv.permissions;
            }
        };

        $rootScope.logout = srv.logout;
        this.retrieveAuthData();
        this.checkAuthentication();

    }])

    .factory('Modals',function(){

        var service = {};

        service.forms = [];
        service.resetHandler = null;

        service.toggleModal = function(modal,type){
            if(service.forms === undefined || service.forms.length <1 ) return;

            var form = service.forms[modal];
            if(type==='open'){
                form.show();
            }else{
                form.hide();
                if(service.resetHandler !== null){
                    service.resetHandler();
                }
            }
        };

        service.closeAll = function(){
            for(var i in service.forms){
                service.forms[i].hide();
            }
            if(service.resetHandler !== null){
                service.resetHandler();
            }
        };

        service.init = function(forms,callback){
            service.forms = forms;
            service.resetHandler = callback;
            return service;
        };

        return service;

    })

    .service('Tools',['$rootScope',function($rootScope){

        var tools = {};

        tools.findIndex = function(needle,haystack,field){
            return _.findIndex(haystack,function(item){

                if(item[field] && needle[field]){
                    return item[field].toString() === needle[field].toString();
                }

                if(item[field] && !needle[field]){
                    return item[field].toString() === needle.toString();
                }

                if(!item[field] && needle[field]){
                    return item.toString() === needle[field].toString();
                }

                if(!item[field] && !needle[field]){
                    return item.toString() === needle.toString();
                }

            });
        };

        tools.isEqual = function(obj1,obj2,fields){
            if(fields === undefined){
                return obj1 === obj2;
            }

            if(Array.isArray(fields)){
                var field;var f;
                for(var i in fields ){
                    field = fields[i];
                    if(obj1[field] !== obj2[field]){
                        return false;
                    }
                }

                return true;

            }else{
                f = obj1[fields] === obj2[fields];
                return f;
            }
        };

        return tools;

    }])

    .factory('Socket',function($rootScope){

        return {
            on: function(channel,callback){
                socket.on(channel,function(message){
                   callback(message);
                });
            },
            emit: function(channel,data){
                socket.emit(channel,data);
            },
            remove: function(channel){
                socket.removeAllListeners(channel);
            },
            disconnect:function(){
              socket.disconnect();
            },
            init:function(){
                if(socket === null || socket === undefined || !socket.connected ){
                  socket = io.connect('http://'+host+":4000");
                  return true;
                }
                return false;
            }
        };
    })

    .directive('ipmask',['$timeout',function($timeout){

        return {
            restrict:'A',
            link:function(scope,ele,attr){

                $timeout(function(){
                    if(attr.ipmask==='ipv4'){
                        angular.element(ele).ipAddress({scope: scope});
                    }
                    else if(attr.ipmask==='ipv6'){
                        angular.element(ele).ipAddress({v:6,scope: scope});
                    }
                },100);
            }
        };

    }])

    .filter("html", ['$sce', function($sce) {
        return function(htmlCode){
            return $sce.trustAsHtml(htmlCode);
        };
    }])

    .filter("src", ['$sce', function($sce) {
        return function(src){
            return $sce.trustAsResourceUrl(src);
        };
    }])

    .filter("toDate", function() {
        return function(dateStr){
            if(dateStr === undefined || dateStr === null || dateStr === ''){
                return '';
            }
            return new Date(dateStr);
        };
    })

    .filter('timestamp',function(){
        return function(date){
            if(date === undefined || date === null || date === ''){
                return '';
            }
            return Math.round(date.getTime()/1000);
        };
    });

})();
