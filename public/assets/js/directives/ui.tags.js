(function () {

	'use strict';
	
	angular
		.module('ui.tags', [])
		.directive('ngTags', [function () {
			return {
				restrict: 'AE',
				scope: {
					placeholder: '@',
					tags: '=ngModel'
				},
				require: 'ngModel',
				replace: true,
				template: '' + 
					'<ul class="input-tags">'+
						'<li ng-repeat="tag in tags track by $index">{{ tag }}<a ng-click="remove($event, $index)" href class="close"><span class="fa fa-times"></span></a></li>' +
						'<li><input type="text" ng-model="tag" ng-keydown="addTag($event)" placeholder="{{ placeholder }}"></li>' +
					'</ul>',
				link: function ($scope, $el, $attr) {
					$scope.blocked = false;
					$scope.tags = $scope.tags ? typeof $scope.tags === 'object' ? $scope.tags : $scope.tags.split(',') : [];
					
					$scope.remove = function ($event, $index) {
						$event.preventDefault();
						$scope.tags.splice($index, 1);
					};
					
					// add tag
					$scope.addTag = function (event) {

						// Enter or ','
						if ( [13,188].indexOf(event.keyCode) > -1 ) {
							event.preventDefault();
							if( $scope.tag.match(/^[ ,]*$/) == null ) {
								if ( $scope.limit && $scope.tags.length > $scope.limit ) return;
								$scope.tags.push($scope.tag);
							}
							$scope.tag = null;
						}

						// remove tag, button backspace
						if ( event.keyCode === 8 && !$scope.tag ) {
							event.preventDefault();
							$scope.tags.pop();
						}
					};
				}
			}
		}]);
})();		