(function () {
	'use stritct';

	angular
		.module('ctrl.tables', [])
		.controller('TableCtrl', ['$scope', 'ngTableParams', '$filter', function ($scope, ngTableParams, $filter) {
	        var data = [
				{ "company": "Entertaiment", "name": "Johny Bravo", "description": "CN", "profit": 120, "status": { type: 'success', value: "Good" }, role: 'Administrator' },
				{ "company": "Entertaiment", "name": "John Smith", "description": "Enormo", "profit": 20, "status": { type: 'danger', value: "Bad" }, role: 'Moderator' },
				{ "company": "Movie", "name": "Brad Pitt", "description": "Enormo", "profit": 250, "status": { type: 'primary', value: "Not finished" }, role: 'Moderator' },
				{ "company": "Movie", "name": "Carney Cox", "description": "Enormo", "profit": 100, "status": { type: 'info', value: "Finieshed" }, role: 'User' },
				{ "company": "Friends", "name": "Chandler Bing", "description": "Friends", "profit": 86, "status": { type: 'success', value: "Good" }, role: 'User'},
				{ "company": "Cox", "name": "Carney", "description": "Enormo", "profit": 600, "status": { type: 'danger', value: "Bad" }, role: 'User'},
				{ "company": "Movie", "name": "Tom Cruze", "description": "Celebrity", "profit": 450, "status": { type: 'danger', value: "Bad" }, role: 'Administrator'},
				{ "company": "Movie", "name": "John Smith", "description": "Celebrity", "profit": 450, "status": { type: 'danger', value: "Bad" }, role: 'Administrator'},
				{ "company": "Movie", "name": "John Smith", "description": "Celebrity", "profit": 450, "status": { type: 'danger', value: "Bad" }, role: 'Administrator'},
				{ "company": "Movie", "name": "John Smith", "description": "Celebrity", "profit": 450, "status": { type: 'danger', value: "Bad" }, role: 'Administrator'},
				{ "company": "Movie", "name": "John Smith", "description": "Celebrity", "profit": 450, "status": { type: 'danger', value: "Bad" }, role: 'Administrator'}
			];

			$scope.tableParams = new ngTableParams({
				page: 1,            // show first page
				count: 10,          // count per page
				sorting: {
					name: 'asc'     // initial sorting
				}
			}, {
				total: data.length, // length of data
				getData: function($defer, params) {
					// use build-in angular filter
					var orderedData = params.sorting() ? $filter('orderBy')(data, params.orderBy()) : data;

					$defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
				}
			});

			$scope.tableParams1 = new ngTableParams({
				page: 1,            // show first page
				count: 10,          // count per page
				sorting: {
					name: 'asc'     // initial sorting
				}
			}, {
				total: data.length, // length of data
				getData: function($defer, params) {
					var orderedData = params.sorting() ? $filter('orderBy')(data, params.orderBy()) : data;
					$defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
				}
			});

			$scope.tableParams2 = new ngTableParams({
				page: 1,
				count: 10
			}, {
				groupBy: 'role',
				total: data.length,
				getData: function($defer, params) {
					var orderedData = params.sorting() ? $filter('orderBy')(data, params.orderBy()) : data;
					$defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
				}
			});
			
		}]);
})();