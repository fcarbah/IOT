(function() {
	'use strict';

	angular
		.module('uxGenie', [])
		.run(['$templateCache', function ($templateCache) {
			$templateCache.put('genieLamp.html', [
				'<div class="genie-lamp-container">',
					'<div class="lamp-wrapper">',
						'<input type="text" class="lamp-input form-control input-lg" ng-keydown="uxLamp.inputKey($event)" ng-model="uxLamp.input" placeholder="Where You want to go?" autofocus>',
						'<section class="lamp-wishes-container" ng-show="uxLamp.granded.length > 0">',
							'<div ng-repeat="wish in uxLamp.granded" ng-mouseenter="uxLamp.selectWish($event)" ng-class="{ focused: wish.selected }" ng-click="uxLamp.grandWish(wish)" class="wish">',
								'<span>{{ wish.name }}</span>',
							'</div>',
						'</section>',
					'</div>',
				'</div>'].join('')
			);
		}])
		.directive('uxLamp', ['$timeout', '$document', '$templateCache', '$compile', '$rootScope', '$window', 
			function ($timeout, $document, $templateCache, $compile, $rootScope, $window) {

			return {
				restrict: 'AC',
				scope: {},
				link: function ($scope, el) {
					$scope.uxLamp = {};
					$scope.uxLamp.input = '';
					$scope.uxLamp.granded = [];
					
					var _visible = false;
					var _change = 0;

					var newScope, template, lampContainer;
					var container = angular.element(document.querySelector('body'));
					
					var rubShortcut = '32'; // Space
					var rubModifier = 'ctrlKey'; // Ctrl

					rubShortcut = parseInt(rubShortcut, 10);
					if (isNaN(rubShortcut)) {
						rubShortcut = rubShortcut[0].charCodeAt(0);
					}

					function _initLamp () {
						if ( _visible ) return false;
						newScope = $scope.$new();
						template = $compile( $templateCache.get('genieLamp.html') )(newScope, function(clonedElement, scope) {});
						lampContainer = template[0].querySelector('.lamp-wishes-container');
						container.append( template );
						template[0].querySelector('input').focus();
						_visible = true;
					}

					function _destroyLamp () {
						newScope.$destroy();
						template.remove();
						_resetState();
						_visible = false;
					}

					function _resetState () {
						$scope.uxLamp.input = '';
						$scope.uxLamp.granded = [];
						_change = 0;
						template = null;
						lampContainer = null;
					}

					// open lamp, CTRL+SPACE
					$document.bind('keydown', function (event) {
						if (event.keyCode === rubShortcut) {
							if (rubModifier) {
								if (event[rubModifier]) {
									event.preventDefault();
									_initLamp();
								}
							} else {
								event.preventDefault();
								_destroyLamp();
							}
						}
					});

					// exit lamp, default ESC
					$document.bind('keydown', function (event) {
						if (event.keyCode === 27 && _visible) {
							event.preventDefault();
							_destroyLamp();
						}
					});

					function changeSelection (change) {
						$timeout(function () {
							if( $scope.uxLamp.granded.length > 0 ) {
								$scope.uxLamp.granded.forEach(function (wish, index) {
									if ( change === index ) {
										wish.selected = true; 
										scrollToWish(index);
									} else {
										wish.selected = false;
									}
								});
							}
						}, 100);
					}

					function scrollToWish (index) {	
						var current = lampContainer.querySelectorAll('.wish')[index].offsetTop;
						var container = lampContainer.clientHeight;
						var sEl = lampContainer.querySelector('.wish').clientHeight;
						var visibleCount = Math.floor(lampContainer.clientHeight / sEl);
						var count = lampContainer.childElementCount;
						
						if ( current - lampContainer.scrollTop > container ) lampContainer.scrollTop += sEl;
						if ( current - lampContainer.scrollTop < sEl  ) lampContainer.scrollTop -= sEl;
					}
					
					$scope.uxLamp.inputKey = function (event) {
						switch(event.keyCode) {
							case 38: // up
								if (_change > 0 ) _change -= 1;
								break;
							case 40: // down
								if (_change < $scope.uxLamp.granded.length ) _change += 1;
								break;
							case 13: // enter
								$scope.uxLamp.granded.forEach(function (wish) {
									if ( wish.selected ) $scope.uxLamp.grandWish(wish);
								});
								break;
							default: 
								_change = 0;
								break;
						}
						changeSelection(_change, event);
					};

					$scope.uxLamp.grandWish = function (wish) {
						$window.location = wish.action;
						_destroyLamp();
					};

					function _getGranded (newVal) {
						$scope.uxLamp.granded = [];
						if ( newVal.length < 1 ) return false;
						$rootScope.wishes.forEach(function (wish, index) {
							var reg = new RegExp(newVal, 'ig');
							if ( wish.name.search(reg) > -1 ) {
								$scope.uxLamp.granded.push(wish);
							}
						});
					}

					$scope.$watch('uxLamp.input', _getGranded);
				}
			};
		}])
		.directive('genieWish', ['$rootScope', function ($rootScope) {
			return {
				scope: true,
				//require: 'uiSref',
				link: function (scope, el, attrs) {
					if ( typeof $rootScope.wishes === 'undefined' ) {
						$rootScope.wishes = [];
					}
					$rootScope.wishes.push({
						name: attrs.genieWish || el.text(),
						action: attrs.href
					});
				}
			};
		}]);
})();