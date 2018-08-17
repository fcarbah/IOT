(function () {
	'use strict';

	angular
		.module('ctrl.c3', [])
		.controller('C3Ctrl', ['$scope', function ($scope) {
			$scope.chartDemo1 = {
				data: {
					columns: [['data1', 120, 80, 140, 120, 140, 180, 200, 180, 140, 200, 50, 40]],
					type: 'bar',
					colors: {
						data1: '#65C9BF'
					}
				},
				axis: {
					y: { show: true },
					x: { show: true }
				},
				legend: { show: true },
				tooltip: { show: true }
			};

			$scope.chartDemo2 = {
				data: {
					columns: [
						['data1', 120, 80, 140, 120, 140, 180, 200, 180, 140, 200, 50, 40]
					],
					type: 'line',
					colors: {
						data1: '#65C9BF'
					}
				},
				axis: {
					y: { show: true },
					x: { show: true }
				},
				legend: { show: true },
				tooltip: { show: true }
			};

			$scope.chartDemo3 = {
				data: {
					columns: [
						['data1', 120, 80, 140, 120, 140, 180, 200, 180, 140, 200, 50, 40],
						['data2', 200, 10, 40, 40, 120, 150, 20, 50, 80, 140, 160, 190]
					],
					type: 'spline',
					colors: {
						data1: '#65C9BF',
						data2: '#3A3367'
					}
				},
				axis: {
					y: { show: true },
					x: { show: true }
				},
				legend: { show: true },
				tooltip: { show: true }
			};

			$scope.chartDemo4 = {
				data: {
					columns: [
						['data1', 120, 80, 140, 120, 140, 180, 200, 180, 140, 200, 50, 40],
						['data2', 200, 10, 40, 40, 120, 150, 20, 50, 80, 140, 160, 190]
					],
					types: {
						data1: 'area-step',
						data2: 'step'
					},
					colors: {
						data1: '#65C9BF',
						data2: '#3A3367'
					}
				},
				axis: {
					y: { show: true },
					x: { show: true }
				},
				legend: { show: true },
				tooltip: { show: true }
			};

			$scope.chartDemo5 = {
				data: {
					columns: [
						['data1', 120, 80, 140, 120, 140, 180, 200, 180, 140, 200, 50, 40],
						['data2', 200, 10, 40, 40, 120, 150, 20, 50, 80, 140, 160, 190]
					],
					types: {
						data1: 'area-spline',
						data2: 'area-spline'
					},
					colors: {
						data1: '#F96D78',
						data2: '#3A3367'
					}
				},
				axis: {
					y: { show: true },
					x: { show: true }
				},
				legend: { show: true },
				tooltip: { show: true }
			};

			$scope.chartDemo6 = {
				data: {
					columns: [
						['data1', 120],
						['data2', 40],
						['data3', 50],
						['data4', 100]
					],
					type:'pie',
					colors: {
						data1: '#5C8CCA',
						data2: '#65C9BF',
						data3: '#F5CC26',
						data4: '#F96D78'
					}
				}
			};

			$scope.chartDemo7 = {
				data: {
					columns: [
						['data1', 120],
						['data2', 40],
						['data3', 50],
						['data4', 100]
					],
					type:'donut',
					colors: {
						data1: '#5C8CCA',
						data2: '#65C9BF',
						data3: '#F5CC26',
						data4: '#F96D78'
					}
				}
			};

			$scope.chartDemo8 = {
				data: {
					columns: [
						['data1', 85.4]
					],
					type:'gauge',
					colors: {
						data1: '#5C8CCA'
					}
				}
			};
		}]);
})();