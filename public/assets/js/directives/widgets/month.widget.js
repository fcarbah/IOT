(function () {
	'use strict';

	angular
		.module('month.widget', [
			{ files: ['assets/js/vendor/thirdparty/moment.js'] }
		])
		.directive('widgetMonth', [function() {
			return {
				restrict: 'EA',
				templateUrl: 'partials/widgets/month.widget.html',
				replace: true,
				scope: true,
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