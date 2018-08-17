(function () {
	'use strict';

	function sideMenuDirective ($window) {
		return {
			restrict: 'A',
			link: function ($scope, $el, $attr) {
				var menu_items = $el[0].querySelectorAll('li[data-state] > a'); 

				angular
					.element(menu_items)
					.on('click', function (e) {
						var _parent = this.parentNode;

						if ( !_isContracted() && _hasSubmenu(this) ) {
							e.preventDefault();
							_parent.dataset.state = _parent.dataset.state === "open" ? "close" : "open";
						} 
					});

				angular
					.element($el[0].querySelectorAll('li[data-state] a[ui-sref]'))
					.bind('click', function (e) {
						if ( _isMobile() ) {
							document.querySelector('body').classList.toggle('offcanvas');
						}
					});

				function _isMobile () {
					return document.querySelector('body').classList.contains('offcanvas');
				}

				function _hasSubmenu (item) {
					return !!item.nextElementSibling; 
				}

				function _restoreClassButtons () {
					// $el[0].querySelectorAll('aside[role="navigation"] nav li[data-state]').dataset.state = 'close';
					// });
				}
				function _isContracted () {
					return document.querySelector('body').classList.contains('contract');
				}
			}
		}
	}

	function sideMenuToggleDirective ($rootScope) {
		return {
			restrict: 'CA',
			link: function ($scope, $el, $attr) {
				var _body = document.querySelector('body');
				var body = angular.element( _body );

				$el.on('click', function (e) {
					e.preventDefault();
	
					// responvive break point
					if ( window.innerWidth <= 767 ) { 
						_body.classList.remove('contract')
						_toggleOffcanvas();
						return true;
					} else if ( !_body.classList.contains('contract') ) {
						[].forEach.call(document.querySelectorAll('aside[role="navigation"] li[data-state="open"]'), function (item) {
							item.dataset.state = "close";
						});
					}
					body.toggleClass('contract');
				});

				function _toggleOffcanvas () {
					document.querySelector('body').classList.toggle('offcanvas'); 
				}
			}
		}
	}

	angular
		.module('ui.sidemenu', [])
		.directive('uiSideMenu', ['$window', sideMenuDirective])
		.directive('sideMenu', ['$rootScope', sideMenuToggleDirective]);
})();