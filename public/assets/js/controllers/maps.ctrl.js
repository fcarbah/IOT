(function () {
	'use strict';

	angular
		.module('ctrl.maps', ['uiGmapgoogle-maps'])
		.config(['uiGmapGoogleMapApiProvider', function (uiGmapGoogleMapApiProvider) {
			uiGmapGoogleMapApiProvider.configure({
				v: '3.17',
				libraries: 'weather,geometry,visualization,places,drawing'
			});
		}])
		.controller('MapsCtrl', ['$scope', 'uiGmapGoogleMapApi', function ($scope, uiGmapGoogleMapApi) {
			var geocoder, drawingManagerOptions;

			$scope.searchLocation = 'London, UK';
			$scope.drawMap = {};
			$scope.currentMap = {
				zoom: 12,
				options: {
					scrollwheel: false
				} 
			};

			uiGmapGoogleMapApi.then(function (maps) {
				geocoder = maps.Geocoder();
				$scope.drawMap = {
					center: { latitude: 51.219053, longitude: 4.404418 }, 
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

			navigator.geolocation.getCurrentPosition(function (pos) {
				$scope.currentMap.center = {
                                    latitude: pos.coords.latitude,
                                    longitude: pos.coords.longitude
				};
			}, function () {
				$scope.currentMap.center = {latitude: 51.219053, longitude: 4.404418};
			});

			$scope.weatherMap = { 
				zoom: 4,
				center: { latitude: 51.219053, longitude: 4.404418 }, 
				options: { 
					scrollwheel: false
				},
				showWeather: true
			};

			$scope.search = {
				zoom: 8,
				center: { latitude: 51.219053, longitude: 4.404418 },
				options: {
					scrollwheel: false
				},
				template: 'searchbox.tpl.html', 
				events: {
					places_changed: function (searchBox) {
						$scope.search.center = {
							latitude: searchBox.getPlaces()[0].geometry.location.lat(),
							longitude: searchBox.getPlaces()[0].geometry.location.lng()
						}
						$scope.$apply();
					}
				}
			};

			$scope.mappyData = {
				'GB' : 5,
				'US' : 3,
			};
			
		}]);
})();