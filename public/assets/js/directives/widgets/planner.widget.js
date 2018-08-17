(function () {
	'use strict';

	angular
		.module('planner.widget', [])
		.directive('widgetPlanner', [function () {
			return {
				restrict: 'EA',
				templateUrl: 'partials/widgets/planner.widget.html',
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
								['data1', 160, 180, 160, 140, 160, 140, 140, 160, 180, 180, 200, 120],
								['data2', 20, 60, 80, 90, 100, 120, 140, 180, 140, 200, 50, 40],
							],
							type: 'area-spline',
							colors: {
								data1: '#65C9BF',
								data2: '#3A3367'
							}
						},
						axis: {
							y: { show: false },
							x: { show: false }
						},
						legend: { show: false },
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