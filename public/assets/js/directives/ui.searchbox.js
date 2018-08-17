(function () {
	'use strict';

	function uiSearchDirective () {
		return {
			restrict: 'A',
			link: function ($scope, $el, $attr) {
				
				$scope.toggleSearch = function (e) {
					e.preventDefault();
					$el.toggleClass('open');
				}
			}
		}
	}

	angular
		.module('ui.searchbox', [])
		.directive('uiSearchBox', [uiSearchDirective]);
})();