(function () {
	'use strict';

	angular
		.module('ui.markdown', [])
		.directive('uiMarkdown', ['$sce', function ($sce) {
			return {
				restrict: 'AC',
				template: '<div ng-bind-html="output"></div>',
				replace: true,
				scope: {
					input: '=uiMarkdown',
					disabled: '=dataSanitize'
				},	
				link: function ($scope, $el, $attr) {
					$scope.output = "";
					$scope.$watch('input', function (text) {
						if (!text) return;
						$scope.output = $sce.trustAsHtml(marked( $scope.input, {
							sanitize: $scope.disabled ? $scope.disabled : false
						}));
					});
				}
			};
		}]);

})();