(function () {
	'use strict';

	angular
		.module('ui.shortcuts', ['mgcrea.ngStrap.modal'])
		.constant('SHORTCUTS', {
			paths: [
				{ name: 'Dashboard', url: 'app.dahsboard' },
				{ name: 'UI Elements / Panels', url: 'app.ui.panels' },
				{ name: 'UI Elements / Grids', url: 'app.ui.grids' },
				{ name: 'UI Elements / Typography', url: 'app.ui.typography' },
				{ name: 'UI Elements / Buttons', url: 'app.ui.buttons' },
				{ name: 'UI Elements / Tables', url: 'app.ui.tables' },
				{ name: 'UI Elements / Components', url: 'app.ui.components' },
				{ name: 'UI Elements / Forms', url: 'app.ui.forms' },
				{ name: 'UI Elements / Icons', url: 'app.ui.icons' },
				{ name: 'UI Elements / Editors', url: 'app.ui.editors' },
				{ name: 'Widgets', url: 'app.widgts' },
				{ name: 'Extras / Timeline', url: 'app.extras.timeline' },
				{ name: 'Extras / Invoice', url: 'app.extras.invoice' },
				{ name: 'Extras / Invoice show', url: 'app.extras.invoice-show' },
				{ name: 'Extras / Form Wizard', url: 'app.extras.wizard' },
				{ name: 'Extras / Crop', url: 'app.extras.crop' },
				{ name: 'Maps / Google maps', url: 'app.maps.gmaps' },
				{ name: 'Tasks', url: 'app.tasks' },
				{ name: 'Charts / C3', url: 'app.charts.c3' },
				{ name: 'Charts / NvD3', url: 'app.charts.nvd3' },
				{ name: 'Mail / Inbox', url: 'mail.inbox' },
				{ name: 'Mail / New mail ', url: 'mail.new' },
				{ name: 'Mail / Read', url: 'mail.read' },
				{ name: 'Pages / Login', url: 'page.login' },
				{ name: 'Pages / Blank', url: 'page.blank' },
				{ name: 'Pages / Lock', url: 'page.lock' },
				{ name: 'Pages / Register', url: 'page.register' },
				{ name: 'Pages / 404', url: 'page.404' },
				{ name: 'Pages / 500', url: 'page.500' },
				{ name: 'Pages / Loading', url: 'page.loading' } 
			] 
		})
		.directive('shortcuts', ['$state', '$document', 'SHORTCUTS',
			function ($state, $document, SHORTCUTS) {

			return {
				restrict: 'A',
				scope: {},
				template: [
					'<section class="lamp-wrapper">',

					'</section>',
				].join(''),
				link: function ($scope, $el, $attr) {

				}
			}
		}]);
})();