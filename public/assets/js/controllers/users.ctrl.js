(function () {
    'use strict';
    
     angular
    
    .module('ctrl.users', ['ngMessages'])
    
    .controller('UsersCtrl', ['$scope','ngTableParams','$filter','$modal','HttpService','AlertService','Modals', function ($scope,ngTableParams,$filter,$modal,HttpService,AlertService,Modals) {
        
        $scope.users=[];
        $scope.userRoles =[];
        $scope.newUser = {username:'',role_id:'',password:'',confirmPassword:''};
        $scope.editUser ={};
        $scope.credentials={id:'',name:'',oldPassword:'',newPassword:''};
        
        $scope.usersTable = new ngTableParams({
            page: 1,            // show first page
            count: 10,          // count per page
            sorting: {
                name: 'asc'     // initial sorting
            }
        }, {
            groupBy:'role',
            total: $scope.users.length, // length of data
            getData: function($defer, params) {
                    // use build-in angular filter
                    var orderedData = params.sorting() ? $filter('orderBy')($scope.users, params.orderBy()) : $scope.users;
                    $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
            }
        });

        $scope.resetUser = function(){
            $scope.newUser = {username:'',role_id:'',password:'',confirmPassword:''};
            $scope.editUser ={};
            $scope.credentials={id:'',oldPassword:'',newPassword:'',name:''};
        };
        
        $scope.loadUsers = function(){
            HttpService.get('/users',$scope.setUsers);
        };
        
        $scope.setUsers = function(res){
            AlertService.show(res);
            if(!res.error){
                $scope.users = res.data.users;
                $scope.userRoles = res.data.userRoles;
                $scope.usersTable.reload();
                $scope.closeAllModals();
            }
        };
        
        $scope.displayResponse = function(res){
            AlertService.show(res);
            if(!res.error){
                $scope.closeAllModals();
            }
        };
        
        $scope.changePassword = function(){
            if($scope.chPassForm.$scope.chPassForm.$valid){
                HttpService.post('/users/changepassword/'+$scope.credentials.id,$scope.credentials,{},$scope.displayResponse);
            }
        };
        $scope.deleteUser = function(){
            if($scope.editUser.id !== undefined){
                HttpService.post('/users/delete/'+$scope.editUser.id,{},{},$scope.setUsers);
            }
        };
        $scope.resetPassword = function(user){
            if(user.id !== undefined){
                HttpService.post('/users/resetpassword/'+user.id,{},{},$scope.displayResponse);
            }
        };
        $scope.saveUser = function(){
            if($scope.addForm.$scope.addForm.$valid){
                HttpService.post('/users/add',$scope.newUser,{},$scope.setUsers);
            }
        }; 
        $scope.suspendUser = function(user){
            if(user.id !== undefined){
                HttpService.post('/users/suspend/'+user.id,{},{},$scope.displayResponse);
            }
        };
        $scope.updateUser = function(){
            if($scope.editForm.$scope.editForm.$valid){
                HttpService.post('/users/edit/'+$scope.editUser.id,$scope.editUser,{},$scope.setUsers);
            }
        };
        
        $scope.toggleUserForm = function(){
            $scope.resetUser();
        };
        
        $scope.toggleEditForm = function(user){
            if(user !== undefined){
                $scope.editUser = angular.copy(user);
                $scope.toggleModal(1,'open');
            }else{
                $scope.toggleModal(1);
            }
        };
        $scope.toggleDeleteForm = function(user){
            if(user !== undefined){
                $scope.editUser = angular.copy(user);
                $scope.delConfirm.message="Are you sure you want to delete User \" "+ user.username+' "';
                $scope.toggleModal(3,'open');
            }else{
                $scope.toggleModal(3);
            }
        };
        $scope.togglePassForm = function(user){
            if(user !== undefined){
                $scope.credentials.id = user.id;
                $scope.credentials.name = user.username;
                $scope.toggleModal(2,'open');
            }else{
                $scope.toggleModal(2);
            }
        };
        
        $scope.delConfirm = {title:'User Deletion Confirmation',message:'',deleteHandler: $scope.deleteUser,cancelHandler:$scope.toggleDeleteForm};       
        
        $scope.addForm=$modal({
            template:'pages/users/modals/user.add.html',
            placement:'center',show:false,scope:$scope
        });
        $scope.editForm=$modal({
            template:'pages/users/modals/user.edit.html',
            placement:'center',show:false,scope:$scope
        });
        $scope.chPassForm=$modal({
            template:'pages/users/modals/ch.password.html',
            placement:'center',show:false,scope:$scope
        });
        $scope.delForm=$modal({
            template:'pages/del.confirmation.html',
            placement:'center',show:false,scope:$scope
        });
        
        $scope.modals = Modals.init([$scope.addForm,$scope.editForm,$scope.chPassForm,$scope.delForm],$scope.resetUser);
        
        $scope.toggleModal = $scope.modals.toggleModal;
        $scope.closeAllModals = $scope.modals.closeAll;
        
        $scope.loadUsers();
        
    }]);
    
})();