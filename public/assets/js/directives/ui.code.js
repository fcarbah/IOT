(function () {
	
	'use strinct';
	
	function codeDirective () {
		return {
			restrict: 'E',
			link: function ($scope, $el, $attr) {
				hljs.highlightBlock($el[0]);
			}
		};
	}

	angular
		.module('ui.code', [])
		.directive('code', [codeDirective]);
})();