(function () {
	'use strict';

	angular
		.module('ctrl.sortable', [])
		.controller('SortableCtrl', ['$scope', 'cfpLoadingBar', 'ngTableParams', '$filter',
			function ($scope, cfpLoadingBar, ngTableParams, $filter) {
			
			$scope.sortablelist = [
				{ id: 1, title: 'Sort item 1', nodes: [
					{ id: 2, title: 'Sort item 2', nodes: [] },
					{ id: 3, title: 'Sort item 3', nodes: [] },
					{ id: 4, title: 'Sort item 4', nodes: [
						{ id: 9, title: 'Sort item 9', nodes: [] },
						{ id: 10, title: 'Sort item 10', nodes: [] }
					] }
				]},
				{ id: 5, title: 'Sort item 5', nodes: [] },
				{ id: 6, title: 'Sort item 6', nodes: [
					{ id: 11, title: 'Sort item 11', nodes: [] },
					{ id: 12, title: 'Sort item 12', nodes: [] },
					{ id: 13, title: 'Sort item 13', nodes: [
						{ id: 14, title: 'Sort item 14', nodes: [] }
					] }
				] },
				{ id: 7, title: 'Sort item 7', nodes: [] },
				{ id: 8, title: 'Sort item 8', nodes: [] }
			];

			$scope.sortablelist2 = [
				{ id: 1, title: 'Sortable element #1', class: 'bg-success' },
				{ id: 2, title: 'Sortable element #2', class: 'bg-danger' },
				{ id: 3, title: 'Sortable element #3', class: 'bg-info' },
				{ id: 4, title: 'Sortable element #4', class: 'bg-primary' },
				{ id: 5, title: 'Sortable element #5', class: 'bg-warning' },
			];
		}]);
})();