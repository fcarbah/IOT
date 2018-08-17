(function () {
	'use strict';

	angular
		.module('ctrl.dashboard', [])
		.controller('DashboardCtrl', ['$scope', '$interval', function ($scope, $interval) {

			$scope.chartDemo8 = {
				data: {
					columns: [
						['data1', 120],
						['data2', 40],
						['data3', 50],
						['data4', 100]
					],
					type: 'donut',
					colors: {
						data1: '#5C8CCA',
						data2: '#65C9BF',
						data3: '#F5CC26',
						data4: '#F96D78'
					},
				},
				padding: {
					top: 15,
					bottom: 15,
					left: 15, 
					right: 15
				},
				legend: {
					show: false
				}
			};

			$scope.chartDemo5 = {
				data: {
					columns: [
						['data1', 60, 80, 95, 110, 160, 120, 80, 60, 40, 60, 80, 100],
						['data2', 200, 10, 40, 40, 120, 150, 20, 50, 80, 140, 160, 190]
					],
					types: {
						data1: 'area-spline',
						data2: 'area-spline'
					},
					colors: {
						data1: '#3A3367',
						data2: '#65C9BF'
					},
				},
				size: {
					height: 300,
				},
				grid: {
					x: { show: true },
					y: { show: true }
				},
				transition: {
					duration: 500
				},
				padding: {
					top: 0,
					bottom: 0,
					left: 0,
					right: 0		
				},
				legend: { show: false },
				tooltip: { show: true },
				axis: {
					y: { show: false },
					x: { show: false }
				},
			};
		}]);
})();