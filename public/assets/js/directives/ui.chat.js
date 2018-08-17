(function () {
	'use strict';

	angular
		.module('ui.chat', [])
		.directive('uiChatToggle', [function () {
			return {
				restrict: 'AC',
				link: function ($scope, $el, $attr) {
					$el.on('click', function (e) {
						e.preventDefault();
						document.querySelector('body').classList.toggle('chat-open');
					});
				}
			}
		}])
		.directive('uiChat', [function(){
			return {
				restrict: 'E', 
				prority: 10,
				scope: {
					// users: '=ngModel'
				}, 
				replace: true,
				templateUrl: 'partials/chat.html',
				link: function($scope, $el, $attr, ngModel) {
					$scope.enter = function (e) {
						e.preventDefault();
						$el[0].classList.add('open-conversation');
					}
					$scope.back = function (e) {
						e.preventDefault();
						$el[0].classList.remove('open-conversation');
					}
				}
			};
		}]);
})();