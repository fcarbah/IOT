(function () {
	'use strict';

	angular
		.module('widgets.ctrl', ['ngVideo'])
		.controller('WidgetsCtrl', ['$scope', 'video', function ($scope, video) {

	        video.addSource('mp4', 'http://www.w3schools.com/html/mov_bbb.mp4', true);
		}]);
})();