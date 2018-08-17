(function () {
    'use strict';

    angular

    .module('ctrl.camera', ['cfp.loadingBar','wu.masonry'])

    .controller('CameraCtrl', ['$scope','AlertService','HttpService','WSService',function ($scope,AlertService,HttpService,WSService) {

        $scope.photos =[];
        $scope.camera = {};
        $scope.executing = false;
        $scope.videoSrc='';
        $scope.defSrc='/assets/img/flat-camera.png';
        $scope.camOn =0;
        $scope.msg ='Camera Off';

        $scope.load = function(){
            if($scope.executing){
                return;
            }
            $scope.executing = true;
            HttpService.get('/camera',$scope.setCamera);
        };
        $scope.setCamera = function(res){
            AlertService.show(res);
            if(!res.error){
                if(res.data.on ===1){
                    $scope.videoSrc='http://project.fcarbah.com:8081';
                }else{
                    $scope.videoSrc='';
                }
                $scope.camera = res.data;
                $scope.camOn = res.data.on === 1? true : false;
                $scope.msg ='Camera Off';
                if(res.data.name !== ''){
                    $scope.photos.unshift({name:res.data.name,path:res.data.path});
                }

            }
            $scope.executing = false;
        };
        $scope.wsCam = function(res){
          $scope.setCamera(res.data);
          $scope.executing = false;
        };
        $scope.wsCamExecuting = function(res){
          $scope.executing = res.data.executing;
          $scope.msg = res.data.msg;
        };
        $scope.toggleCamera = function(){
            if($scope.camOn){
                $scope.on();
            }else{
                $scope.off();
            }
        };

        $scope.off = function(){
            if($scope.executing){
                return;
            }
            $scope.executing = true;
            $scope.msg="Powering Off...";
            HttpService.get('/camera/off');
        };
        $scope.on = function(){
            if($scope.executing){
                return;
            }
            $scope.executing = true;
            $scope.msg="Powering On...";
            HttpService.get('/camera/on');
        };
        $scope.takePicture = function(){
            if($scope.executing){
                return;
            }
            $scope.videoSrc='';
            $scope.executing = true;
            $scope.msg="Capturing Photo...";

            HttpService.get('/camera/photo');
        };

        //video.addSource('mp4', 'http://www.w3schools.com/html/mov_bbb.mp4', true);
        $scope.load();
        WSService.subscribe('cam_update',$scope.wsCam);
        WSService.subscribe('cam_executing',$scope.wsCamExecuting);

    }])

    .controller('GalleryCtrl', ['$scope','AlertService','HttpService','$modal','Modals', function ($scope,AlertService,HttpService,$modal,Modals) {

        $scope.photos = [];
        $scope.photo = {};

        $scope.resetPhoto = function(){
            $scope.photo = {};
        };

        $scope.load = function(){
            HttpService.get('/camera/photos',$scope.setPhotos);
        };
        $scope.setPhotos = function(res){
            AlertService.show(res);
            if(!res.error){
                $scope.photos = res.data;
                $scope.closeAllModals();
            }
        };

        $scope.delete = function(){
            if($scope.photo.path !== undefined && $scope.photo.path !==''){
                HttpService.post('/camera/photos/delete',$scope.photo,{},$scope.setPhotos);
            }
        };

        $scope.toggleDeleteForm=function(photo){
            if(photo !== undefined){
                $scope.delConfirm.message ='Are you sure you want to delete photo "'+photo.name+'"?';
                $scope.photo = angular.copy(photo);
                $scope.toggleModal(0,'open');
            }else{
                $scope.delConfirm.message='';
                $scope.photo = {};
                $scope.toggleModal(0);
            }
        };

        $scope.delConfirm = {title:'Delete Confirmation',message:'',deleteHandler: $scope.delete,cancelHandler:$scope.toggleDeleteForm};


        $scope.delForm=$modal({
            template:'pages/del.confirmation.html',
            placement:'center',show:false,scope:$scope
        });

        $scope.modals = Modals.init([$scope.delForm],$scope.resetPhoto);

        $scope.toggleModal = $scope.modals.toggleModal;
        $scope.closeAllModals = $scope.modals.closeAll;

        $scope.load();

    }]);

})();
