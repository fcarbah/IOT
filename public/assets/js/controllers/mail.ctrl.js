(function () {
	'use strict';

	angular
		.module('mail.ctrl', ['cfp.loadingBar'])
		.controller('MailCtrl', ['$scope', '$rootScope', 'cfpLoadingBar', '$timeout', '$state',
			function ($scope, $rootScope, cfpLoadingBar, $timeout, $state) {

			$rootScope.expanded = true;
			$scope.short = 360;
			$scope.mail = {};

			$scope.senders = "John Smith";
			$scope.recipients = "Piter Jackson";

			$rootScope.$on('$stateChangeSuccess', function (event, data) {
				$timeout(function () {
					cfpLoadingBar.complete();
				}, 500);

				if( $state.is('app.mail.inbox') ) {
					$rootScope.expanded = true;
				}
			});

			$rootScope.$on('$stateChangeStart', function (event, data) {
				window.scrollTo(0,0);
				$scope.show_page = false;
				cfpLoadingBar.start();
			});

			$scope.expandToggle = function (e) {
				e.preventDefault();
				$scope.expanded = !$scope.expanded;
			}
			
			$scope.toggleAll = function () {
				$scope.mails.forEach(function (mail) {
					mail.check = !mail.check;
				});
			}

			$scope.mails = [
				{ id: 1 ,title: 'New awesome smarthphone!', content: '<h4 class="bold">Header #1</h4><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere illum eius reprehenderit reiciendis quibusdam maiores sequi, dicta ducimus officiis laudantium possimus atque aliquam quisquam placeat, tempore expedita autem. </p><p>Neque, accusantium. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est eligendi odio accusantium consectetur magnam cumque vel, facere possimus voluptatum molestias qui consequuntur, nulla ex rem debitis illo sit culpa! Est. Lorem ipsum dolor sit amet, consectetur adipisicing elit. </p><h5 class="bold">Header #2</h5><p>Natus cupiditate qui, asperiores placeat dignissimos quaerat a debitis provident quas, quisquam corporis accusamus nesciunt nihil, cum explicabo repellendus sit sint repellat. Lorem ipsum dolor sit amet, consectetur adipisicing elit. </p><ul><li>Enim minus</li><li>tenetur</li><li>Amet ratione a minus</li></ul><p>Inventore sunt ducimus sit aut quia, sint dicta? Veritatis omnis iste maxime laboriosam, eius fuga!</p>', to: 'John Smith', from: 'Michael Jackson', date: new Date(), tag: { type: 'success', name: 'Confirmed' }, files: [{ name: 'picture_1202.jpg', url: '' }, { name: 'image_007.jpg', url: '' }, { name: 'picture_1201.jpg', url: ''}] },
				{ id: 2 ,title: 'New awesome smarthphone!', content: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere illum eius reprehenderit reiciendis quibusdam maiores sequi, dicta ducimus officiis laudantium possimus atque aliquam quisquam placeat, tempore expedita autem. Neque, accusantium.', to: 'John Smith', from: 'Michael Jackson', date: new Date(),  tag: { type: 'primary', name: 'Invoice' }, },
				{ id: 3 ,title: 'New awesome smarthphone!', content: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere illum eius reprehenderit reiciendis quibusdam maiores sequi, dicta ducimus officiis laudantium possimus atque aliquam quisquam placeat, tempore expedita autem. Neque, accusantium.', to: 'John Smith', from: 'Michael Jackson', date: new Date(), tag: { type: 'danger', name: 'Important' } },
				{ id: 4 ,title: 'New awesome smarthphone!', content: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere illum eius reprehenderit reiciendis quibusdam maiores sequi, dicta ducimus officiis laudantium possimus atque aliquam quisquam placeat, tempore expedita autem. Neque, accusantium.', to: 'John Smith', from: 'Michael Jackson', date: new Date(), tag: { type: 'info', name: 'Job offer' }, },
				{ id: 5 ,title: 'New awesome smarthphone!', content: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere illum eius reprehenderit reiciendis quibusdam maiores sequi, dicta ducimus officiis laudantium possimus atque aliquam quisquam placeat, tempore expedita autem. Neque, accusantium.', to: 'John Smith', from: 'Michael Jackson', date: new Date(), files: [{ name: 'picture_1202.jpg', url: '' }] },
				{ id: 6 ,title: 'New awesome smarthphone!', content: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere illum eius reprehenderit reiciendis quibusdam maiores sequi, dicta ducimus officiis laudantium possimus atque aliquam quisquam placeat, tempore expedita autem. Neque, accusantium.', to: 'John Smith', from: 'Michael Jackson', date: new Date(), files: [{ name: 'picture_1201.jpg', url: ''}] },
				{ id: 7 ,title: 'New awesome smarthphone!', content: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere illum eius reprehenderit reiciendis quibusdam maiores sequi, dicta ducimus officiis laudantium possimus atque aliquam quisquam placeat, tempore expedita autem. Neque, accusantium.', to: 'John Smith', from: 'Michael Jackson', date: new Date(), files: [{ name: 'picture_1202.jpg', url: '' }, { name: 'image_007.jpg', url: '' }, { name: 'picture_1201.jpg', url: ''}] },
				{ id: 8 ,title: 'New awesome smarthphone!', content: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere illum eius reprehenderit reiciendis quibusdam maiores sequi, dicta ducimus officiis laudantium possimus atque aliquam quisquam placeat, tempore expedita autem. Neque, accusantium.', to: 'John Smith', from: 'Michael Jackson', date: new Date(), files: [{ name: 'picture_1201.jpg', url: ''}] },
				{ id: 9 ,title: 'New awesome smarthphone!', content: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere illum eius reprehenderit reiciendis quibusdam maiores sequi, dicta ducimus officiis laudantium possimus atque aliquam quisquam placeat, tempore expedita autem. Neque, accusantium.', to: 'John Smith', from: 'Michael Jackson', date: new Date(), tag: { type: 'warning', name: 'Private' }, },
				{ id: 10 ,title: 'New awesome smarthphone!', content: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere illum eius reprehenderit reiciendis quibusdam maiores sequi, dicta ducimus officiis laudantium possimus atque aliquam quisquam placeat, tempore expedita autem. Neque, accusantium.', to: 'John Smith', from: 'Michael Jackson', date: new Date() },
				{ id: 11 ,title: 'New awesome smarthphone!', content: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere illum eius reprehenderit reiciendis quibusdam maiores sequi, dicta ducimus officiis laudantium possimus atque aliquam quisquam placeat, tempore expedita autem. Neque, accusantium.', to: 'John Smith', from: 'Michael Jackson', date: new Date() },
				{ id: 12 ,title: 'New awesome smarthphone!', content: 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere illum eius reprehenderit reiciendis quibusdam maiores sequi, dicta ducimus officiis laudantium possimus atque aliquam quisquam placeat, tempore expedita autem. Neque, accusantium.', to: 'John Smith', from: 'Michael Jackson', date: new Date() }
			];


		}])
		.controller('SingleMailCtrl', ['$scope', '$state', '$rootScope', function ($scope, $state, $rootScope) {
			$rootScope.expanded = false;

			if ( $state.params.id ) {
				$scope.$parent.mails.forEach(function (mail) {
					if ( mail.id === parseInt($state.params.id) ) {
						$scope.mail = mail;
					}
				});
			}

			$scope.cancel = function (e) {
				e.preventDefault();
				$rootScope.expanded = true;
				$state.go('app.mail.inbox');
			}
		}]);
})();