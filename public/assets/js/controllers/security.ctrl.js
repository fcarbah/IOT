
(function () {
    'use strict';

    angular
    
    .module('ctrl.security', [])
    
    .controller('AccessControlCtrl', ['$scope', 'ngTableParams','$filter','$modal','HttpService','Modals','AlertService', function ($scope,ngTableParams,$filter,$modal,HttpService,Modals,AlertService) {
        $scope.ipFilters=[]; 
        $scope.ipPattern = /(\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b)|(\b([A-F0-9]{1,4}:){7}([A-F0-9]{1,4})\b)/i;
        $scope.ipOptional = /(\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b)|(\b([A-F0-9]{1,4}:){7}([A-F0-9]{1,4})\b)|(^$)|(\_{1,3}(\.\_{1,3}){3})/i;
        $scope.config = {enableLocalNet:true,enableIpFilter:true};

        $scope.newFilter = {startIp:'',endIp:'',accessType:''};
        $scope.editFilter={};
        $scope.accessTypes=[];
        
        $scope.resetFilter = function(){
            $scope.newFilter = {startIp:'',endIp:'',accessType:''};
            $scope.editFilter={};
        };
        
        $scope.ipFilterTable = new ngTableParams({
            page: 1,            // show first page
            count: 10,          // count per page
            sorting: {
                name: 'asc'     // initial sorting
            }
        }, {
            groupBy: 'accessType',
            total: $scope.ipFilters.length, // length of data
            getData: function($defer, params) {
                // use build-in angular filter
                var orderedData = params.sorting() ? $filter('orderBy')($scope.ipFilters, params.orderBy()) : $scope.ipFilters;
                $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
            }
        });

        $scope.loadACL = function(){
            HttpService.get('/security/acl',$scope.setACL);
        };
        
        $scope.setACL = function(res){
            AlertService.show(res);
            if(!res.error){
                $scope.ipFilters = res.data.filters;
                $scope.accessTypes = res.data.modes;
                $scope.config = res.data.config;
                $scope.closeAllModals();
                $scope.ipFilterTable.reload();
            }
        };
        
        $scope.setEndIp = function(){
            if($scope.newFilter.endIp ==='' || $scope.newFilter.endIp ==='0.0.0.0'|| $scope.newFilter.endIp ==='___.___.___.___'){
                $scope.newFilter.endIp = $scope.newFilter.startIp;
            }
        };
        $scope.deleteFilter = function(){
            if($scope.editFilter.id !== undefined){
                HttpService.post('/security/acl/delete/'+$scope.editFilter.id,{},{},$scope.setACL);
            }
        };
        $scope.saveIpFilter = function(){
            if($scope.addForm.$scope.addForm.$valid){
                $scope.setEndIp();
                HttpService.post('/security/acl/add',$scope.newFilter,{},$scope.setACL);
            } 
        };
        $scope.updateIpFilter = function(){
            if($scope.editForm.$scope.editForm.$valid){
                $scope.setEndIp();
                HttpService.post('/security/acl/edit/'+$scope.editFilter.id,$scope.editFilter,{},$scope.setACL);
            } 
        };
        
        $scope.saveConfig = function(){
           HttpService.post('/config/security',$scope.config,{},$scope.setConfig); 
        };
        $scope.setConfig = function(res){
            AlertService.show(res);
            if(!res.error){
                $scope.config = res.data;
            }
        };
        
        $scope.toggleAclForm = function(){
            $scope.resetFilter();
        };
        $scope.toggleEditForm = function(filter){
            if(filter !== undefined){
                $scope.editFilter = angular.copy(filter);
                $scope.toggleModal(1,'open');
            }else{
                $scope.toggleModal(1);
            }
        };
        $scope.toggleDeleteForm = function(filter){
            if(filter !== undefined){
                $scope.editFilter = angular.copy(filter);
                $scope.delConfirm.message="Are you sure you want to delete this IP Filter: <strong>"+filter.startIp+' - '+ filter.endIp+'</strong> ?';
                
                $scope.toggleModal(2,'open');
            }else{
                $scope.delConfirm.message='';
                $scope.toggleModal(2);
            }
        };
        
        $scope.delConfirm = {title:'IP Filter Deletion Confirmation',message:'',deleteHandler: $scope.deleteFilter,cancelHandler:$scope.toggleDeleteForm};       
        
        $scope.addForm=$modal({
            template:'pages/security/modals/acl.add.html',
            placement:'center',show:false,scope:$scope
        });
        $scope.editForm=$modal({
            template:'pages/security/modals/acl.edit.html',
            placement:'center',show:false,scope:$scope
        });

        $scope.delForm=$modal({
            template:'pages/del.confirmation.html',
            placement:'center',show:false,scope:$scope
        });
        
        $scope.modals = Modals.init([$scope.addForm,$scope.editForm,$scope.delForm],$scope.resetFilter);
        
        $scope.toggleModal = $scope.modals.toggleModal;
        $scope.closeAllModals = $scope.modals.closeAll;
        
        $scope.loadACL();
        
    }])

    .controller('AccountPolicyCtrl', ['$scope', 'ngTableParams', '$filter','$modal','HttpService','Modals','AlertService', function ($scope,ngTableParams,$filter,$modal,HttpService,Modals,AlertService) {
        $scope.policies =[]; 
        
        $scope.lockoutDurations=[];
        $scope.resetDurations=[];
        $scope.counts=[];
        $scope.userRoles =[];
        $scope.editPolicy={};
        
        $scope.apTable = new ngTableParams({
            page: 1,            // show first page
            count: 10,          // count per page
            sorting: {
                name: 'asc'     // initial sorting
            }
        }, {
            total: $scope.policies.length, // length of data
            getData: function($defer, params) {
                // use build-in angular filter
                var orderedData = params.sorting() ? $filter('orderBy')($scope.policies, params.orderBy()) : $scope.policies;
                $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
            }
        });
        
        $scope.loadPolicies = function(){
            HttpService.get('/security/ap',$scope.setPolicies);
        };
        $scope.setPolicies = function(res){
            AlertService.show(res);
            if(!res.error){
                $scope.policies = res.data.policies;
                $scope.userRoles = res.data.userRoles;
                $scope.lockoutDurations = res.data.lockoutDurations;
                $scope.resetDurations = res.data.resetDurations;
                $scope.counts = res.data.counts;
                $scope.closeAllModals();
                $scope.apTable.reload();
            }
        };
        
        $scope.editAP = function(policy){
            $scope.editPolicy = angular.copy(policy);
            $scope.toggleModal(0,'open');
        };
        
        $scope.resetAP = function(){
            $scope.editPolicy = {};
        };
        
        $scope.saveAP = function(){
            if($scope.editForm.$scope.editForm.$valid){
                HttpService.post('/security/ap/edit/'+$scope.editPolicy.id,$scope.editPolicy,{},$scope.setPolicies);
            }
        };
        
        $scope.editForm=$modal({
            template:'pages/security/modals/ap.edit.html',
            placement:'center',show:false,scope:$scope
        });
        
        $scope.modals = Modals.init([$scope.editForm],$scope.resetAP);
        
        $scope.toggleModal = $scope.modals.toggleModal;
        $scope.closeAllModals = $scope.modals.closeAll;
        
        $scope.loadPolicies();
        
    }]);
    
})();