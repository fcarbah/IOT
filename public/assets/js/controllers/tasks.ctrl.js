(function () {
	'use strict';

	angular
		.module('tasks.ctrl', [])
		.controller('TasksCtrl', ['$scope', function ($scope) {
			$scope.open_project = {};

			$scope.users = [
				{name: 'John Smith', avatar: 'assets/img/avatar/avatar75.png' },
				{name: 'John Smith', avatar: 'assets/img/avatar/avatar75.png' },
				{name: 'John Smith', avatar: 'assets/img/avatar/avatar75.png' },
				{name: 'John Smith', avatar: 'assets/img/avatar/avatar75.png' },
				{name: 'John Smith', avatar: 'assets/img/avatar/avatar75.png' },
			];

			$scope.pie = [
				{ key: "Project #1", y: 5 },
				{ key: "Project #2", y: 2 },
				{ key: "Project #3", y: 9 },
			];
			$scope.pie_options = {
				chart: {
					type: 'pieChart',
					height: 295,
					x: function(d){return d.key;},
					y: function(d){return d.y;},
					color: ['#5C8CCA','#65C9BF','#3A3367','#F5CC26','#F96D78','#40474d'],
					showLabels: false,
					transitionDuration: 500,
					labelThreshold: 0.01,
					legend: {
						margin: {
							top: 5,
							right: 0,
							bottom: 0,
							left: 0
						}
					}
				}
			};

			$scope.projects = [
				{
					title: 'Project #1',
					tasks: [
						{				
							title: 'Deploy application to server',
							author: 'John Smith',
							assing: 'John Smith',
							status: false,
							date: new Date('01.20.2014'),
							priority: 1 
						},
						{				
							title: 'Send payment information to the XYZ client',
							author: 'John Smith',
							assing: 'John Smith',
							status: false,
							date: new Date('02.04.2014'),
							priority: 2
						},
						{				
							title: 'Fix bug with authentication via Facebook API',
							author: 'John Smith',
							status: false,
							date: new Date('03.02.2014'),
							priority: 3 
						},
						{				
							title: 'Build landing page based on Bootstrap Framework',
							author: 'John Smith',
							assing: 'John Smith',
							status: false,
							date: new Date('04.10.2014'),
							priority: 4 
						},
						{				
							title: 'Build landing page based on Bootstrap Framework',
							author: 'John Smith',
							status: false,
							date: new Date('04.10.2014'),
							priority: 4 
						},
						{				
							title: 'Build landing page based on Bootstrap Framework',
							author: 'John Smith',
							status: false,
							date: new Date('04.10.2014'),
							priority: 4 
						},
						{				
							title: 'Build landing page based on Bootstrap Framework',
							author: 'John Smith',
							assing: 'John Smith',
							status: false,
							date: new Date('04.10.2014'),
							priority: 4 
						},
						{				
							title: 'Build landing page based on Bootstrap Framework',
							author: 'John Smith',
							assing: 'John Smith',
							status: false,
							date: new Date('04.10.2014'),
							priority: 4 
						},
						{				
							title: 'Build landing page based on Bootstrap Framework',
							author: 'John Smith',
							assing: 'John Smith',
							status: false,
							date: new Date('04.10.2014'),
							priority: 4 
						},
						{				
							title: 'Build landing page based on Bootstrap Framework',
							author: 'John Smith',
							assing: 'John Smith',
							status: false,
							date: new Date('04.10.2014'),
							priority: 4 
						}
					]
				},
				{
					title: 'Project #2',
					tasks: [
						{				
							title: 'Transfer project to social department 1',
							author: 'John Smith',
							assing: 'John Smith',
							status: false,
							duration: {
								start: new Date('12.12.2014'),
								end: new Date('03.12.2014')
							}
						},
						{				
							title: 'Transfer project to social department 2',
							author: 'John Smith',
							status: false,
							date: new Date('11.12.2014')
						},
						{				
							title: 'Transfer project to social department 3',
							author: 'John Smith',
							assing: 'John Smith',
							status: false,
							duration: {
								start: new Date('12.12.2014'),
								end: new Date('03.12.2014')
							}
						},
						{				
							title: 'Transfer project to social department 4',
							author: 'John Smith',
							status: false,
							date: new Date('11.12.2014'),
							duration: {
								start: new Date('12.12.2014'),
								end: new Date('03.12.2014')
							}
						}
					]
				}
			];
			$scope.open_project = $scope.projects[0];

			$scope.openProject = function (e) {
				e.preventDefault();
				$scope.open_project = this.project;
			}

			$scope.addTask = function (e) {
				if(e.keyCode === 13) {
					if ($scope.task.length < 1 ) return;
					$scope.open_project.tasks.unshift({
						title: $scope.task,
						author: 'John Smith',
						status: false,
						date: new Date()
					});
					$scope.task = '';
				}
			}

			function volatileChart (startPrice, volatility, numPoints) {
				var rval =  [];
				var now =+new Date();
				numPoints = numPoints || 100;
				for(var i = 1; i < numPoints; i++) {

					rval.push({x: now + i * 1000 * 60 * 60 * 24, y: startPrice});
					var rnd = Math.random();
					var changePct = 2 * volatility * rnd;
					if ( changePct > volatility) {
						changePct -= (2*volatility);
					}
					startPrice = startPrice + startPrice * changePct;
				}
				return rval;
			}

		}]);
})();