
(function () {
	'use strict';

	angular
		.module('ui.c3', [])
		.directive('uiCharts', [function () {
			return {
				scope: {
					options: '=uiCharts'
				},
				restrict: 'AC',
				link: function($scope, $el, $attr) {
                                    if (!$scope.options) return; 
                                    $el[0].classList.add('ui-chart');
                                    var defaults = angular.extend($scope.options, { bindto: $el[0] })
                                    var chart = c3.generate(defaults);
                                        
				}
			};
		}])
		.directive('uiMiniChart', ['$interval', function ($interval) {
			return {
				scope: {
					options: '=uiMiniChart'
				},
				restrict: 'AC',
				link: function ($scope, $el, $attr) {
					var i = 0;
					var demoData = [
						['data1', 20, 60, 80, 100, 120, 160, 180, 200, 200, 200],
						['data1', 120, 130, 10, 90, 200, 160, 120, 100, 20, 30],
						['data1', 10, 180, 120, 90, 190, 10, 40, 80, 20, 20],
						['data1', 120, 80, 140, 120, 140, 180, 200, 180, 140, 200],
						['data1', 120, 130, 10, 90, 200, 160, 120, 100, 20, 30],
					];					

					$el[0].classList.add('ui-chart');
					var options = angular.extend($scope.options, { bindto: $el[0] } );
					var chart = c3.generate(options);
				}
			}
		}]);
})();