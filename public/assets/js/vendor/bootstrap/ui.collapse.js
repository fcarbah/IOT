(function () {
	'use strict';

	angular
		.module('ui.collapse', [])
		.directive('uiCollapse', [function () { 
		return {
			restrict: 'AC',
			link: function ($scope, $el, $attr) {
				var items = $el[0].querySelectorAll('.panel [data-toggle="collapse"]');

				angular
					.element( items )
					.on('click', function (e) {
						e.preventDefault();
						var hash = e.target.hash;
						var target = $el[0].querySelector(hash);
						var panels = $el[0].querySelectorAll('.panel-collapse.collapse.in');

						[].forEach.call(panels, function (item) {
							if ( target === item ) return;
							item.classList.remove('in');
						});

						target.classList.toggle('in');
					});
			}
		};
	}]);
	
})();