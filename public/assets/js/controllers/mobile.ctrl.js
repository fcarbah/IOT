(function () {
    'use strict';

    angular
    
    .module('ctrl.mobile', ['ngMessages'])
    
    .controller('KeysCtrl', ['$scope','ngTableParams', '$filter','$modal','HttpService','AlertService','Modals', function ($scope,ngTableParams,$filter,$modal,HttpService,AlertService,Modals) {
        $scope.keys = [];
        $scope.key = {name:'',id:''};
        
        $scope.keysTable = new ngTableParams({
            page: 1,            // show first page
            count: 10,          // count per page
            sorting: {
                name: 'asc'     // initial sorting
            }
        }, {
            total: $scope.keys.length, // length of data
            getData: function($defer, params) {
                // use build-in angular filter
                var orderedData = params.sorting() ? $filter('orderBy')($scope.keys, params.orderBy()) : $scope.keys;
                $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
            }
        });
        
        $scope.load = function(){
            HttpService.get('/mobile/keys',$scope.set);
        };
        $scope.set = function(res){
            AlertService.show(res);
            if(!res.error){
                $scope.keys = res.data;
                $scope.resetKey();
                $scope.keysTable.reload();
                $scope.closeAllModals();
            }
        };
        
        $scope.resetKey = function(){
           $scope.key = {name:'',id:''}; 
        };
        
        $scope.addKey = function(){
            if($scope.addMForm.$scope.addForm.$valid){
                HttpService.post('/mobile/keys/add',$scope.key,{},$scope.set);
            }
        };
        $scope.refreshKey = function(key){
            HttpService.post('/mobile/keys/refresh/'+key.id,key,{},$scope.set);
        };
        $scope.deleteKey = function(){
            if($scope.key.id !== ''){
                HttpService.post('/mobile/keys/delete/'+$scope.key.id,$scope.key,{},$scope.set);
            }
        };
        
        $scope.toggleDeleteForm=function(key){
            if(key !== undefined){
                $scope.delConfirm.message ='Are you sure you want to delete "'+key.name+'" Mobile API Key?';
                $scope.key = angular.copy(key);
                $scope.toggleModal(1,'open');
            }else{
                $scope.delConfirm.message='';
                $scope.key = {};
                $scope.toggleModal(1);
            }
        };
        
               
        $scope.delConfirm = {title:'Delete Confirmation',message:'',deleteHandler: $scope.deleteContact,cancelHandler:$scope.toggleDeleteForm};
       
        
        $scope.addMForm=$modal({
            template:'pages/mobile/modals/key.add.html',
            placement:'center',show:false,scope:$scope
        });
        $scope.delMForm=$modal({
            template:'pages/del.confirmation.html',
            placement:'center',show:false,scope:$scope
        });
        
        $scope.modals = Modals.init([$scope.addMForm,$scope.delMForm],$scope.resetKey);
        
        $scope.toggleModal = $scope.modals.toggleModal;
        $scope.closeAllModals = $scope.modals.closeAll;
        
        $scope.load();
   
    }]);
    
})();


