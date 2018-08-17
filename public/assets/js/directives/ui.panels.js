(function () {
	'use strcit';

	angular
		.module('ui.panels', [])
		.directive('panelOptions', [function () {
			return {
				restrict: 'C',
				link: function ($scope, $el, $attr) {
					var panel = $el[0].parentNode.parentNode;
					var body = panel.querySelector('.panel-body');

					var refresh = $el[0].querySelector('[data-toggle="refresh"]');
					var minimize = $el[0].querySelector('[data-toggle="minimize"]');
					var close = $el[0].querySelector('[data-toggle="close"]');

					angular.element(refresh).on('click', function (e) {
						e.preventDefault();

					});

					angular.element(close).on('click', function (e) {
						e.preventDefault();
						panel.style.display = 'none';
					});

					angular.element(minimize).on('click', function (e) {
						e.preventDefault();
						panel.classList.toggle('collapsed');
						body.classList.toggle('collapse');					
					});
				}
			}
	}]);

	
})();