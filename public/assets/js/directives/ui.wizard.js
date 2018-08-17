(function () {
	'use strict';

	function formWizardDirective ($compile, $timeout) {
		return {
			restrict: 'AE',
			priority: 100,
			scope: true,
			link: function ($scope, $el, $attr) {
				$scope.progress = 0;
				$scope.index = 0;

				var links = $el[0].querySelectorAll('.tabs li > *'),
					lis = $el[0].querySelectorAll('.tabs li'),
					panes = $el[0].querySelectorAll('.wizard-pane');
				var	nextBtn, prevBtn;

				var progress = $compile('<div class="bar" style="width: {{ progress }}%"></div>')($scope),
					tabs = $el[0].querySelector('.tabs');
					angular.element(tabs).after(progress);

				angular.element(links).on('click', function (e) {
					e.preventDefault();
					$scope.index = _getIndex.call(this);
				});

				$scope.$next = function (e) {
					if(e) e.preventDefault();
					if ($scope.index < links.length-1) $scope.index++;
				}

				$scope.$prev = function (e) {
					if(e) e.preventDefault();
					if ($scope.index > 0) $scope.index--;
				}

				$scope.$first = function (e) {
					if (e) e.preventDefault();
					$scope.index = 0;
				}

				$scope.$last = function (e) {
					if (e) e.preventDefault();
					$scope.index = links.length - 1;
				}

				function _getIndex () {
					return [].indexOf.call(links, this);
				}

				function _nodeWalk (index) {
					if ( index === (lis.length-1) ) $scope.progress = 100;
					else {
						var p = 1/lis.length * 100; 
						$scope.progress = p * index + p/2;
					}
					[].forEach.call(lis, function (node, i) {
						if (i <= index ) {
							node.classList.add('active');
							node.querySelector(':first-child').disabled = false;
						} else {
							node.classList.remove('active');
							node.querySelector(':first-child').disabled = true;
						}
					});
				}

				function _paneWalk (index) {
					[].forEach.call(panes, function (pane, i) {
						if ( i === index ) pane.classList.add('active');
						else pane.classList.remove('active');
					});
				}

				$scope.$watch('index', function (newPosition) {
					_nodeWalk(newPosition);
					_paneWalk(newPosition);
				});
			}
		};
	}

	angular
		.module('ui.wizard', [])
		.directive('uiFormWizard', ['$compile', '$timeout', formWizardDirective]);

})();