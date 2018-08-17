
(function () {
    'use strict';

    angular
    
    .module('ctrl.config', ['ngMessages'])
    
    .controller('TempCtrl', ['$scope','HttpService','AlertService', function ($scope,HttpService,AlertService) {
        
        $scope.defTemp={};
        
        $scope.currTemp={};
        
        $scope.temp = {};
        
        $scope.loadTempConfig = function(){
            HttpService.get('/config/temperature',$scope.setTempConfig);
        };
        
        $scope.setTempConfig = function(res){
            AlertService.show(res);
            if(!res.error){
                $scope.defTemp = res.data.baseTemp;
                $scope.currTemp = res.data.temp;
                $scope.temp = angular.copy($scope.currTemp);
            }
        };
        
        $scope.saveTempConfig = function(){
            HttpService.post('config/temperature',$scope.temp,{},$scope.setTempConfig);
        };
        
        $scope.loadTempConfig();
        
    }])

    .controller('NotifCtrl', ['$scope','HttpService','Tools','AlertService', function ($scope,HttpService,Tools,AlertService) {
        
        $scope.availableNotifs ={};
        
        $scope.defNotif={};
        
        $scope.currNotif = {};
        
        $scope.showSaveBtn=false;
        
        $scope.selectedNotifConfig = function(obj,obj2,field){
            return Tools.isEqual(obj,obj2,field);
        };
        
        $scope.loadNotifConfig = function(){
            HttpService.get('/config/notification',$scope.setNotifConfig);
        };
        
        $scope.setNotifConfig = function(res){
            AlertService.show(res);
            if(!res.error){
                $scope.availableNotifs = res.data.alertIntervals;
                $scope.defNotif = res.data.conf;
                $scope.currNotif = angular.copy(res.data.conf);
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
            
            $scope.showSaveBtn=true;// = (!Tools.isEqual($scope.defNotif.contacts,$scope.currNotif.contacts,'id') || 
                    //!Tools.isEqual($scope.defNotif.emergency,$scope.currNotif.emergency,'id')) ? true : false;

        };
        
        $scope.saveNotifConfig = function(){
           HttpService.post('config/notification',$scope.defNotif,{},$scope.setNotifConfig);
        };
                
        $scope.loadNotifConfig();
        
    }])

    .controller('ContactCtrl', ['$scope', 'ngTableParams', '$filter','$modal','HttpService','Modals','AlertService', function ($scope,ngTableParams,$filter,$modal,HttpService,Modals,AlertService) {

        $scope.alertTypes=[];
        $scope.types =[];
        $scope.contacts=[];

        $scope.contactsTable = new ngTableParams({
            page: 1,            // show first page
            count: 10,          // count per page
            sorting: {
                name: 'asc'     // initial sorting
            }
        }, {
            groupBy: 'type',
            total: $scope.contacts.length, // length of data
            getData: function($defer, params) {
                    // use build-in angular filter
                    var orderedData = params.sorting() ? $filter('orderBy')($scope.contacts, params.orderBy()) : $scope.contacts;

                    $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
            }
        });

        $scope.newContact={name:'',phone:'',email:'',alertTypes:[],type:''};
        $scope.contact = {};
        $scope.resetNewContact = function(){
            $scope.newContact={name:'',phone:'',email:'',alertTypes:[],type:''};
            $scope.contact = {};
        };
        
        $scope.loadContacts = function(){
            HttpService.get('/config/contacts',$scope.setContacts);
        };
        
        $scope.setContacts = function(res){
            AlertService.show(res);
            if(!res.error){
                $scope.contacts = res.data.contacts;
                $scope.alertTypes = res.data.alertTypes;
                $scope.types = res.data.types;
                $scope.contactsTable.reload();
                $scope.resetNewContact();
                $scope.contact = {};
                $scope.closeAllModals();
            }
        };
        $scope.deleteContact = function(){
            if($scope.contact.canEdit){
                HttpService.post('/config/contacts/delete/'+$scope.contact.id,{},{},$scope.setContacts);
            }
        };
        $scope.saveContact = function(){            
            if($scope.addMForm.$scope.contactForm.$valid){
                HttpService.post('/config/contacts/add',$scope.newContact,{},$scope.setContacts);
            }
        };
        $scope.updateContact = function(){
            if($scope.editMForm.$scope.editForm.$valid){
                HttpService.post('/config/contacts/edit/'+$scope.contact.id,$scope.contact,{},$scope.setContacts);
            }
        };
        
        $scope.toggleContactForm=function(){
            //$scope.showContactForm = !$scope.showContactForm;
            $scope.resetNewContact();
        };
        
        $scope.toggleEditForm=function(contact){
            if(contact !== undefined){
               $scope.contact = angular.copy(contact);
               $scope.toggleModal(1,'open');
            }else{
                $scope.contact = {};
                $scope.toggleModal(1);
            }
        };
        $scope.toggleDeleteForm=function(contact){
            if(contact !== undefined){
                $scope.delConfirm.message ='Are you sure you want to delete "'+contact.name+'"?';
                $scope.contact = angular.copy(contact);
                $scope.toggleModal(2,'open');
            }else{
                $scope.delConfirm.message='';
                $scope.contact = {};
                $scope.toggleModal(2);
            }
        };
        
               
        $scope.delConfirm = {title:'Delete Confirmation',message:'',deleteHandler: $scope.deleteContact,cancelHandler:$scope.toggleDeleteForm};
       
        
        $scope.addMForm=$modal({
            template:'pages/config/modals/contact.add.html',
            placement:'center',show:false,scope:$scope
        });
        $scope.editMForm=$modal({
            template:'pages/config/modals/contact.edit.html',
            placement:'center',show:false,scope:$scope
        });
        $scope.delMForm=$modal({
            template:'pages/del.confirmation.html',
            placement:'center',show:false,scope:$scope
        });
        
        $scope.modals = Modals.init([$scope.addMForm,$scope.editMForm,$scope.delMForm],$scope.resetNewContact);
        
        $scope.toggleModal = $scope.modals.toggleModal;
        $scope.closeAllModals = $scope.modals.closeAll;
        
        $scope.loadContacts();
        
    }])

    .controller('NetworkCtrl', ['$scope','HttpService','AlertService', function ($scope,HttpService,AlertService) {
          
        $scope.modes = [];
        
        $scope.mode = {};
        $scope.ipPattern = /(\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b)|(\b([A-F0-9]{1,4}:){7}([A-F0-9]{1,4})\b)/i;
        $scope.ipOptional = /(\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b)|(\b([A-F0-9]{1,4}:){7}([A-F0-9]{1,4})\b)|(^$)|(\_{1,3}(\.\_{1,3}){3})/i;
        $scope.showIpConfigForm= false;
        $scope.showConfigEditBtn= false;
        
        $scope.ipInfo = {};
        $scope.defIpInfo = {};
        $scope.dynamicIp ={};
        $scope.newIpInfo = {};
        $scope.mac='';
        
        $scope.resetIpInfo = function(){
            $scope.newIpInfo = $scope.defIpInfo;
        };
        
        $scope.loadNetConfig = function(){
            HttpService.get('/config/network',$scope.setNetConfig);
        };
        
        $scope.setNetConfig = function(res){
            AlertService.show(res);
            if(!res.error){
                $scope.resetIpInfo();
                $scope.modes = res.data.networkModes;
                $scope.mac = res.data.mac;
                $scope.mode = res.data.mode;
                $scope.ipInfo = res.data.ipInfo;
                $scope.dynamicIp = res.data.dynamicIp;
                $scope.defIpInfo = angular.copy(res.data.ipInfo);
                $scope.newIpInfo = angular.copy(res.data.ipInfo);
                $scope.showIpConfigForm = false;
                $scope.showConfigEditBtn= $scope.mode===1?false:true;
            }
        };
        
        $scope.saveIpConfig = function(){
            if($scope.ipform.$valid){

                $scope.ipInfo = angular.copy($scope.newIpInfo);
                $scope.resetIpInfo();
                $scope.showIpConfigForm = false;
                $scope.showConfigEditBtn= true;
                $scope.saveNetConfig();
            }
        };
        
        $scope.saveNetConfig = function(){
           HttpService.post('/config/network',{mode:$scope.mode,staticIp:$scope.ipInfo,dynamicIp:$scope.dynamicIp,mac:$scope.mac},{},$scope.setNetConfig); 
        };
        
        $scope.toggleIpConfigForm = function(mode){
            $scope.mode  = mode.id;
            $scope.resetIpInfo();
            if(mode.id === 1){
                $scope.showIpConfigForm = false;
                $scope.showConfigEditBtn = false;
                $scope.ipInfo = angular.copy($scope.defIpInfo);
                $scope.saveNetConfig();
            }else{
                $scope.showIpConfigForm = true;
                $scope.showConfigEditBtn = true;
            }
        };
        
        $scope.toggleConfigForm = function(){
            $scope.showIpConfigForm = !$scope.showIpConfigForm && $scope.mode !== 1;
            $scope.resetIpInfo();
        };
        
        $scope.loadNetConfig();
        
    }])
        
    .controller('WirelessCtrl', ['$scope','ngTableParams','$filter','$modal','HttpService','Modals','AlertService', function ($scope,ngTableParams,$filter,$modal,HttpService,Modals,AlertService) {
        
        $scope.wirelessNetworks=[];
        
        $scope.authTypes =[];
        
        $scope.wirelessNetwork={ssid:'',authType:'',password:'',autoConnect:true};
        $scope.credentials = {id:'',password:'',cpassword:''};
        $scope.editNetwork={};
        $scope.wirelessConfig ={};
        
        $scope.showWirelessForm = false;
        $scope.showEditForm = false;
        
        $scope.wirelessTable = new ngTableParams({
            page: 1,            // show first page
            count: 10,          // count per page
            sorting: {
                name: 'asc'     // initial sorting
            }
        }, {
            total: $scope.wirelessNetworks.length, // length of data
            getData: function($defer, params) {
                    // use build-in angular filter
                    var orderedData = params.sorting() ? $filter('orderBy')($scope.wirelessNetworks, params.orderBy()) : $scope.wirelessNetworks;
                    $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
            }
        });
        
        $scope.addToWirelessTable = function(){
            $scope.wirelessTable.data.push($scope.wireless);
        };
        
        $scope.loadWireless = function(){
            HttpService.get('/config/wireless',$scope.setWireless);
        };
        
        $scope.setWireless = function(res){
            AlertService.show(res);
            if(!res.error){
                $scope.closeAllModals();
                $scope.wirelessNetworks = res.data.networks;
                $scope.authTypes = res.data.authTypes;
                $scope.wirelessConfig = res.data.config;
                $scope.wirelessTable.reload();
            }
            
        };
        
        $scope.resetWireless = function(){
            $scope.wirelessNetwork={ssid:'',authType:'',password:'',autoConnect:true};
            $scope.editNetwork={};
            $scope.credentials = {id:'',password:'',cpassword:''};
        };
        $scope.changePassword = function(){
            if($scope.chPForm.$scope.chPassForm.$valid && $scope.credentials.id !=='' ){
                HttpService.post('/config/wireless/changepassword/'+$scope.credentials.id,$scope.credentials,{},$scope.handlePasswordChange);
            }
        };
        $scope.handlePasswordChange = function(res){
            AlertService.show(res);
            if(!res.error){
                $scope.toggleModal(2);
                $scope.resetWireless();
            }
        };
        $scope.deleteWifi = function(){
            HttpService.post('/config/wireless/delete/'+$scope.editNetwork.id,{},{},$scope.setWireless);
        };
        $scope.saveWireless = function(){
            if($scope.addForm.$scope.wirelessForm.$valid){
                HttpService.post('/config/wireless/add',$scope.wirelessNetwork,{},$scope.setWireless);
            }
        };
        $scope.updateWireless = function(){
           if($scope.editForm.$scope.wirelessEditForm.$valid){
                HttpService.post('/config/wireless/edit/'+$scope.editNetwork.id,$scope.editNetwork,{},$scope.setWireless);
            } 
        };
        $scope.passwordRequired = function(net){
            var f= net.authType !== ''  && net.authType !== 'Open (No Encryption)' && net.authType !=='MAC-based access control (No Encryption)';
            if(!f){
                $scope.editNetwork.password='';
                $scope.wirelessNetwork.password=''
            }
            return f;
        };
        
        $scope.toggleWirelessForm = function(){
            $scope.resetWireless();
        };

        $scope.toggleEditForm=function(network){
            if(network !== undefined){
               $scope.editNetwork = angular.copy(network);
               $scope.toggleModal(1,'open');
            }else{
                $scope.toggleModal(1);
            }
        };
        $scope.togglePassForm=function(network){
            if(network !== undefined){
               $scope.credentials.id = angular.copy(network.id);
               $scope.chPass.title = "Change Wifi Password \""+network.ssid+"\"";
               $scope.toggleModal(2,'open');
            }else{
                $scope.toggleModal(2);
                $scope.chPass.title='';
            }
        };
        $scope.toggleDeleteForm=function(network){
            if(network !== undefined){
                $scope.delConfirm.message ='Are you sure you want to delete "'+network.ssid+'"?';
                $scope.editNetwork = angular.copy(network);
                $scope.toggleModal(3,'open');
            }else{
                $scope.delConfirm.message='';
                $scope.toggleModal(3);
            }
        };
        
        $scope.delConfirm = {title:'Wifi Deletion Confirmation',message:'',deleteHandler: $scope.deleteWifi,cancelHandler:$scope.toggleDeleteForm};
        $scope.chPass = {title:'',changeHandler:$scope.changePassword,cancelHandler:$scope.togglePassForm};
       
        
        $scope.addForm=$modal({
            template:'pages/config/modals/wifi.add.html',
            placement:'center',show:false,scope:$scope
        });
        $scope.editForm=$modal({
            template:'pages/config/modals/wifi.edit.html',
            placement:'center',show:false,scope:$scope
        });
        $scope.chPForm=$modal({
            template:'pages/changepassword.html',
            placement:'center',show:false,scope:$scope
        });
        $scope.delForm=$modal({
            template:'pages/del.confirmation.html',
            placement:'center',show:false,scope:$scope
        });
        
        $scope.modals = Modals.init([$scope.addForm,$scope.editForm,$scope.chPForm,$scope.delForm],$scope.resetWireless);
        
        $scope.toggleModal = $scope.modals.toggleModal;
        $scope.closeAllModals = $scope.modals.closeAll;

        $scope.loadWireless();
        
    }]);
    
})();