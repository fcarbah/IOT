(function () {
	'use strict';

	angular
		.module('balance.widget', [
		])
		.directive('widgetBalance', [function () {
			return {
				restrict: 'EA',
				templateUrl: 'partials/widgets/balance.widget.html',
				replace: true,
				scope: true,
				controller: function ($scope) {
					$scope.chartDemo = {
						padding: {
							bottom: -8,
							left: -5,
							right: -5
						},
						size: {
							height: 100
						},
						interaction: {
							enabled: false
						},
						point: {
							show: false
						},
						data: {
							columns: [
								['data1', 120, 80,  140, 120, 140, 180, 200, 180, 140, 200, 50, 40],
								['data2', 0,  200, 120, 40,  80,  100, 120, 20,  40,  120, 10, 50]
							],
							type: 'area-spline',
							colors: {
								data1: '#3A3367',
								data2: '#65C9BF'
							}
						},
						axis: {
							y: { show: false },
							x: { show: false }
						},
						legend: { 
							show: false
						},
						tooltip: { show: false }
					};
				},
				link: function($scope, iElm, iAttrs, controller) {
					$scope.current = moment();

					$scope.$next = function (e) {
						e.preventDefault();
						$scope.current.subtract(1,'month');
					}

					$scope.$prev = function (e) {
						e.preventDefault();
						$scope.current.add(1,'month');
					}
				}
			};
		}]);
})();