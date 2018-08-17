(function () {
	'use strict';

	angular
		.module('ui.multiselect', [])
		.directive('uiMultiselect', [function () {
			return {
				restrict: 'AE',
				transclude: true,
				replace: true,
				scope: {
					data: '=ngOptions',
					model: '=ngModel'
				},
				priority: 10,
				require: 'ngModel',
				templateUrl: "partials/tpl/multiselect.html", 
				link: function ($scope, $el, $attr, ngModel) {

					$scope.select = function (e) {
						e.preventDefault();
						this.item.selected = true;

						$scope.model.push(this.item);
					}
					$scope.remove = function (e) {
						e.preventDefault();
						this.item.selected = false;

						$scope.model.splice(this.$index, 1);
					}
				}
			};
		}]);
})();