angular
    .module('ui.routes', ['ui.router', 'oc.lazyLoad','myapp'])
    // .config(function ($ocLazyLoadProvider) {
    // 	$ocLazyLoadProvider.config({
    // 		debug: true
    // 	});
    // })
    .config(['$stateProvider', '$urlRouterProvider', '$locationProvider',
        function ($stateProvider, $urlRouterProvider, $locationProvider) {

        $urlRouterProvider.when('',['$state','$match',function($state,$match){
            $state.go('home');
        }]);

        $urlRouterProvider.otherwise('/404');

        $stateProvider

        .state('home',{
            url:'/',
            views: {
                '@': {
                controller: 'LoginCtrl',
                templateUrl: 'pages/index.html',
                }
            },
            data: { authRequired: false},
            resolve: {
                load: function ($ocLazyLoad) {
                    return $ocLazyLoad.load([
                        //{ name: 'ngVideo', files: ['assets/js/vendor/ng-video.js'] },
                        { name: 'cfp.loadingBar',files:['assets/js/vendor/loading-bar.js']},
                        { name: 'ui-notification', files: ['assets/js/vendor/angular/angular-ui-notification.js'] },
                        { name: 'ctrl.login', files: ['assets/js/controllers/login.ctrl.js'] }
                    ], { serie: true });
                }
            }
        })

                //initial setup
        .state('app.setup', {
            url: '/setup',
            views: {
                '@': {
                    controller: 'SetupCtrl',
                    templateUrl:'pages/admin/setup.html'
                }
            },
            data: { authRequired: true},
            resolve: {
                load: function ($ocLazyLoad) {
                    return $ocLazyLoad.load([
                        { name: 'vr.directives.slider', files: ['assets/js/vendor/angular/angular-slider.js'] },
                        { name: 'cfp.loadingBar',files:['assets/js/vendor/loading-bar.js']},
                        { name: 'ui-notification', files: ['assets/js/vendor/angular/angular-ui-notification.js'] },
                        { name: 'ui.wizard', files: ['assets/js/directives/ui.wizard.js'] },
                        { name: 'ctrl.setup', files: ['assets/js/controllers/init.setup.ctrl.js'] }
                    ]);
                }
            }
        })

        .state('notfound', {
            url: '/404',
            data: { authRequired: false},
            views: {
                '@': {
                    templateUrl: 'pages/404.html'
                }
            }
        })

        .state('forbidden', {
            url: '/403',
            data: { authRequired: false},
            views: {
                '@': {
                    templateUrl: 'pages/403.html'
                }
            }
        })

        // root route
        .state('app', {
            abstract: true,
            templateUrl: 'pages/home.html',
            controller: 'MainCtrl',
            data: {authRequired: true},
            resolve: {
                load: function ($ocLazyLoad) {
                    return $ocLazyLoad.load([
                        // thirdparty
                        /*{ files: [
                                'assets/js/vendor/thirdparty/moment.js',
                                'assets/js/vendor/thirdparty/d3.js',
                                'assets/js/vendor/thirdparty/c3.js'
                        ] },*/

                        { name: 'cfp.loadingBar', files: ['assets/js/vendor/loading-bar.js'] },
                        { name: 'ui-notification', files: ['assets/js/vendor/angular/angular-ui-notification.js'] },
                        { name: 'angular.filter', files: ['assets/js/filters/angular-filter.js'] },
                        { name: 'mb-scrollbar', files: ['assets/js/vendor/mb-scrollbar.js'] },
                        { name: 'nemLogging', files: ['assets/js/vendor/angular/angular-simple-logger.js'] },
                        { name: 'uiGmapgoogle-maps', files: ['assets/js/vendor/angular/angular-google-maps.js'] },
                        { name: 'services', files: ['assets/js/services.js'] },
                        { name: 'ctrl.main', files: ['assets/js/controllers/main.ctrl.js'] },

                        // ui elements
                        { name: 'ui.sidemenu', files: ['assets/js/directives/ui.sidemenu.js'] },
                        { name: 'ui.panels', files: ['assets/js/directives/ui.panels.js'] },
                        { name: 'ui.dropdown', files: ['assets/js/vendor/bootstrap/ui.dropdown.js'] },
                        { name: 'ui.tabs', files: ['assets/js/vendor/bootstrap/ui.tab.js'] },
                        { name: 'ui.collapse', files: ['assets/js/vendor/bootstrap/ui.collapse.js'] },
                        { name: 'ui.code', files: ['assets/js/directives/ui.code.js', 'assets/js/vendor/thirdparty/highlight.pack.js'] },
                        { name: 'ui.tree', files: ['assets/js/vendor/angular/angular-ui-tree.js'] },
                        { name: 'ui.mask', files: ['assets/js/vendor/bootstrap/ui.mask.js'] },
                        { name: 'ui.select', files: ['assets/js/vendor/angular/angular-ui-select.js'] },
                        { name: 'ui.markdown', files: ['assets/js/directives/ui.makrdown.js', 'assets/js/vendor/thirdparty/marked.js'] },
                        { name: 'ui.multiselect', files: ['assets/js/directives/ui.multiselect.js'] },
                        { name: 'ui.tags', files: ['assets/js/directives/ui.tags.js'] },
                        { name: 'mgcrea.ngStrap.helpers.dimensions', files: ['assets/js/vendor/bootstrap/dimensions.js'] },
                        { name: 'mgcrea.ngStrap.helpers.parseOptions', files: ['assets/js/vendor/bootstrap/parse-options.js'] },
                        { name: 'mgcrea.ngStrap.helpers.dateParser', files: ['assets/js/vendor/bootstrap/date-parser.js'] },
                        { name: 'mgcrea.ngStrap.helpers.dateFormatter', files: ['assets/js/vendor/bootstrap/date-formatter.js'] },
                        { name: 'mgcrea.ngStrap.tooltip', files: ['assets/js/vendor/bootstrap/tooltip.js'] },
                        { name: 'mgcrea.ngStrap.select', files: ['assets/js/vendor/bootstrap/select.js'] },
                        { name: 'mgcrea.ngStrap.tooltip', files: ['assets/js/vendor/bootstrap/tooltip.js'] },
                        { name: 'mgcrea.ngStrap.popover', files: ['assets/js/vendor/bootstrap/popover.js'] },
                        { name: 'mgcrea.ngStrap.modal', files: ['assets/js/vendor/bootstrap/modal.js'] },

                        // vendor
                        { name: 'ngTable', files: ['assets/js/vendor/ng-table.js']},
                        { name: 'vr.directives.slider', files: ['assets/js/vendor/angular/angular-slider.js'] },

                    ])
                }
            },
        })


        // admin panel
        .state('app.admin', {
            abstract: true,
            views: {
                'sidebar': { templateUrl: 'pages/sidebar.html' }
            }
        })

        // dashboard
        .state('app.admin.dashboard', {
            url: '/dashboard',
            views: {
                'content@app': {
                    controller: 'DashboardCtrl',
                    templateUrl:'pages/admin/dashboard.html'
                }
            },
            resolve: {
                load: function ($ocLazyLoad) {
                    return $ocLazyLoad.load([
                        //{ name: 'ui.c3', files: ['assets/js/directives/charts/c3.js'] },
                        {files:['bower_components/angular-c3-simple/dist/angular_c3_simple.min.js']},
                        { name: 'ctrl.dashboard', files: ['assets/js/controllers/dashboard1.ctrl.js'] }
                    ]);
                }
            }
        })


        // end


        //config
        .state('app.admin.config',{
            abstract:true,
            url:'/config',
            resolve:{
                load: function($ocLazyLoad){
                    return $ocLazyLoad.load([
                        { name: 'ctrl.config', files: ['assets/js/controllers/config.ctrl.js'] }
                    ]);
                }
            }
        })
        //contacts config
        .state('app.admin.config.contacts', {
            url: '/contacts',
            views: {
                'content@app': {
                    controller: 'ContactCtrl',
                    templateUrl: 'pages/config/contacts.html'
                }
            }
        })
        //temperature config
        .state('app.admin.config.temp', {
            url: '/temperature',
            views: {
                'content@app': {
                    controller: 'TempCtrl',
                    templateUrl: 'pages/config/temperature.html'
                }
            }
        })
        //network settings
        .state('app.admin.config.network', {
            url: '/network',
            views: {
                'content@app': {
                    controller: 'NetworkCtrl',
                    templateUrl: 'pages/config/network.html'
                }
            }
        })
        //notification settings
        .state('app.admin.config.notif', {
            url: '/notification',
            views: {
                'content@app': {
                    controller: 'NotifCtrl',
                    templateUrl: 'pages/config/notification.html'
                }
            }
        })
        //wireless settings
        .state('app.admin.config.wireless', {
            url: '/wireless',
            views: {
                'content@app': {
                    controller: 'WirelessCtrl',
                    templateUrl: 'pages/config/wireless.html'
                }
            }
        })

        //end config

        //device section
        .state('app.admin.device',{
            abstract:true,
            url:'/device',
            resolve:{
                load: function($ocLazyLoad){
                    return $ocLazyLoad.load([
                        { name: 'ctrl.device', files: ['assets/js/controllers/device.ctrl.js'] }
                    ]);
                }
            }
        })
        //info
        .state('app.admin.device.info', {
            url: '/info',
            views: {
                'content@app': {
                    controller: 'InfoCtrl',
                    templateUrl: 'pages/device/info.html'
                }
            }
        })
        //logs
        .state('app.admin.device.log', {
            url: '/logs',
            views: {
                'content@app': {
                    controller: 'LogCtrl',
                    templateUrl: 'pages/device/logs.html'
                }
            }
        })

        //end device


        //alarm messages
        .state('app.admin.alarm',{
            abstract:true,
            url:'/alarm',
            views: {
                //'sidebar@app': { templateUrl: 'partials/mail/sidebar.html' },
                'content@app': {
                    controller: 'MessageCtrl',
                    templateUrl: 'pages/alarm/messages.html'
                }
            },
            resolve:{
                load: function($ocLazyLoad){
                    return $ocLazyLoad.load([
                        { name: 'ctrl.alarm', files: ['assets/js/controllers/alarm.ctrl.js'] },
                        { name: 'angular.filter', files: ['assets/js/filters/angular-filter.js'] },
                    ]);
                }
            }
        })

        .state('app.admin.alarm.messages', {
            url: '/notifications'
        })

        .state('app.admin.alarm.messages.message', {
            url: '/:id',
            views: {
                'subcontent@app.admin.alarm': {
                    controller: 'SingleMessageCtrl',
                    templateUrl: 'pages/alarm/message.html'
                }
            }
        })
        //end alarm messages


        //mobile section

        .state('app.admin.mobile',{
            abstract:true,
            url:'/mobile',
            resolve:{
                load: function($ocLazyLoad){
                    return $ocLazyLoad.load([
                        { name: 'ctrl.mobile', files: ['assets/js/controllers/mobile.ctrl.js'] }
                    ]);
                }
            }
        })

        //api keys
        .state('app.admin.mobile.keys', {
            url: '/keys',
            views: {
                'content@app': {
                    controller: 'KeysCtrl',
                    templateUrl: 'pages/mobile/keys.html'
                }
            }
        })

        //end mobile


        //security section

        .state('app.admin.security',{
            abstract:true,
            url:'/security',
            resolve:{
                load: function($ocLazyLoad){
                    return $ocLazyLoad.load([
                        { name: 'ctrl.security', files: ['assets/js/controllers/security.ctrl.js'] }
                    ]);
                }
            }
        })
        //access control
        .state('app.admin.security.acl', {
            url: '/access-control',
            views: {
                'content@app': {
                    controller: 'AccessControlCtrl',
                    templateUrl: 'pages/security/accesscontrol.html'
                }
            }
        })
        //account policies
        .state('app.admin.security.ap', {
            url: '/account-policies',
            views: {
                'content@app': {
                    controller: 'AccountPolicyCtrl',
                    templateUrl: 'pages/security/accountpolicies.html'
                }
            }
        })

        //end security section


        //users management

        .state('app.admin.users', {
            url: '/users',
            views: {
                'content@app': {
                    controller: 'UsersCtrl',
                    templateUrl: 'pages/users/users.html'
                }
            },
            resolve: {
                load: function ($ocLazyLoad) {
                    return $ocLazyLoad.load([
                            //{ name: 'ngVideo', files: ['assets/js/vendor/ng-video.js'] },
                            { name: 'ctrl.users', files: ['assets/js/controllers/users.ctrl.js'] }
                    ], { serie: true });
                }
            }
        })

        //end users management

        //camera

        .state('app.admin.camera', {
            abstract: true,
            url: '/camera',
            resolve: {
                load: function ($ocLazyLoad) {
                    return $ocLazyLoad.load([
                        {files:[
                            'bower_components/jquery-bridget/jquery.bridget.js','bower_components/eventEmitter/EventEmitter.js',
                            'bower_components/matches-selector/matches-selector.js','bower_components/doc-ready/doc-ready.js',
                            'bower_components/get-style-property/get-style-property.js','bower_components/eventie/eventie.js',
                            'bower_components/get-size/get-size.js','bower_components/outlayer/item.js',
                            'bower_components/outlayer/outlayer.js','bower_components/masonry/masonry.js',
                            'bower_components/imagesloaded/imagesloaded.js'
                        ]},
                        { name: 'wu.masonry', files: ['bower_components/angular-masonry/angular-masonry.js'] },
                        { name: 'ctrl.camera', files: ['assets/js/controllers/camera.ctrl.js'] }
                    ], { serie: true });
                }
            }
        })
        .state('app.admin.camera.index',{
            url:'',
            views: {
                'content@app': {
                    controller: 'CameraCtrl',
                    templateUrl: 'pages/camera/camera.html'
                }
            }
        })
        .state('app.admin.camera.gallery', {
            url: '/gallery',
            views: {
                'content@app': {
                    controller: 'GalleryCtrl',
                    templateUrl: 'pages/camera/gallery.html'
                }
            }
        });

        //endcamera

}])
/*.run(['$rootScope','$state',function($rootScope, $state){
    //If the route change failed due to authentication error, redirect them out
    $rootScope.$on('$routeChangeError', function(event, toState, toParams, fromState, fromParams){
        if(!fromParams){
            $state.transitionTo("home");
        }
    });
}]);*/

    .run(['$rootScope','$state','$timeout','AuthService',function ($rootScope, $state,$timeout, AuthService) {

            $rootScope.$on("$stateChangeStart", function(event, toState, toParams, fromState, fromParams){
                $rootScope.state = toState.name;
                if (toState.data.authRequired && !AuthService.isAuthenticated){
                    $rootScope.state=null;
                    // User isn’t authenticated
                    event.preventDefault();
                    $state.go("home");
                }
                else if(!toState.data.authRequired && AuthService.isAuthenticated && toState.name !== 'notfound' && toState.name !== 'forbidden'){
                    $rootScope.state=null;
                    event.preventDefault();
                    $state.go("app.admin.dashboard");
                }

                if(!AuthService.setupComplete && toState.name.indexOf('app') >= 0 && toState.name.indexOf('app.setup') < 0){
                    $rootScope.state=null;
                    event.preventDefault();
                    $state.go("app.setup");
                }

                if(AuthService.setupComplete && toState.name.indexOf('app.setup') >= 0){
                    $rootScope.state=null;
                    event.preventDefault();
                    $state.go("app.admin.dashboard");
                }

            });

    }]);
