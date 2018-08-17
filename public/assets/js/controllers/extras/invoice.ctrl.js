(function () {
	'use strict';

	angular
		.module('ctrl.invoice', [])
		.controller('InvoiceCtrl', ['$scope', function ($scope) {

			$scope.invoideDemo = [
				{ "number": "12/12/2014", "name": "Johny Bravo", "address": "2nd avenue", "price": 120, "status": { type: 'success', value: "Good" } },
				{ "number": "12/12/2014", "name": "John Smith", "address": "Second avenue", "price": 20, "status": { type: 'danger', value: "Bad" } },
				{ "number": "20/12/2014", "name": "Brad Pitt", "address": "Enormo 1a", "price": 250, "status": { type: 'primary', value: "Not finished" } },
				{ "number": "20/12/2014", "name": "Carney Cox", "address": "Enormo 1a", "price": 100, "status": { type: 'info', value: "Finieshed" } },
				{ "number": "01/01/2014", "name": "Chandler Bing", "address": "Newyorker 2a", "price": 86, "status": { type: 'success', value: "Good" } },
				{ "number": "1/ABC/2014", "name": "Carney", "address": "Newyorker 2a", "price": 600, "status": { type: 'danger', value: "Bad" } },
				{ "number": "10/10/2014", "name": "Tom Cruze", "address": "Invoice 12a", "price": 450, "status": { type: 'danger', value: "Bad" } },
				{ "number": "10/10/2014", "name": "John Smith", "address": "Invoice 12a", "price": 450, "status": { type: 'danger', value: "Bad" } },
				{ "number": "10/10/2014", "name": "John Smith", "address": "Invoice 12a", "price": 450, "status": { type: 'danger', value: "Bad" } },
				{ "number": "10/10/2014", "name": "John Smith", "address": "Invoice 12a", "price": 450, "status": { type: 'danger', value: "Bad" } },
				{ "number": "10/10/2014", "name": "John Smith", "address": "Invoice 12a", "price": 450, "status": { type: 'danger', value: "Bad" } }
			];
			
			$scope.$toggleAll = function (e) {
				$scope.invoideDemo.forEach(function (item) {
					item.selected = !item.selected;
				});
			}

			$scope.$print = function (e, name, selector) {
				e.preventDefault();
			}
			
		}]);
})();