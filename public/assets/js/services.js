(function () {
    'use strict';

    angular
    .module('services', ['uiGmapgoogle-maps'])
    .config(['uiGmapGoogleMapApiProvider', function (uiGmapGoogleMapApiProvider) {
        uiGmapGoogleMapApiProvider.configure({
            v: '3.17',
            libraries: 'weather,geometry,visualization,places,drawing',
            key:'AIzaSyD9OYXTcnRIMtrGgVa3n45LaMmLyRd7vUY'
        });
    }])

    .service('MapService', ['$rootScope', 'uiGmapGoogleMapApi', function ($rootScope, uiGmapGoogleMapApi) {
          
        
        $rootScope.drawMap={};
        
        var geocoder, drawingManagerOptions;
        
        
        
        this.drawCurrent = function(lat,long){
            $rootScope.currentMap = {
                zoom: 18,
                options: {
                        scrollwheel: false
                } 
            };
        
            $rootScope.currentMap.center = {
                latitude: lat,
                longitude: long
            };
            
            $rootScope.marker = {
                coords:angular.copy($rootScope.currentMap.center),
                options:{labelClass:'marker_labels',labelAnchor:'12 60',labelContent:'My Device'}
            };
            
        };
        
        this.draw = function(lat,long){
            uiGmapGoogleMapApi.then(function (maps) {
                geocoder = maps.Geocoder();
                $rootScope.drawMap = {
                    center: { latitude: lat, longitude: long }, 
                    zoom: 8, 
                    bounds: {},
                    options: { 
                        scrollwheel: false 
                    },
                    drawingManagerOptions: {
                        drawingMode: maps.drawing.OverlayType.MARKER,
                        drawingControl: true,
                        drawingControlOptions: {
                            position: maps.ControlPosition.TOP_CENTER,
                            drawingModes: [
                                maps.drawing.OverlayType.MARKER,
                                maps.drawing.OverlayType.CIRCLE,
                                maps.drawing.OverlayType.POLYGON,
                                maps.drawing.OverlayType.POLYLINE,
                                maps.drawing.OverlayType.RECTANGLE
                            ]	
                        }
                    },
                    circleOptions: {
                        fillColor: '#ffff00',
                        fillOpacity: 1,
                        strokeWeight: 5,
                        clickable: false,
                        editable: true,
                        zIndex: 1
                    }
                };
            });
        };
        
        //return mapservice;
                    
    }]);
    
})();