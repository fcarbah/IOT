(function () {
	'use strict';

	angular
		.module('ctrl.elements', [])
		.controller('ElementsCtrl', ['$scope', function ($scope) {

			$scope.message = "# Markdown Live Example\n---\n##### Example list:\n\n* list item 1\n* list item 2\n* list item 3 \n\n##### Plain text:\nLorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate debitis ex, ratione a animi nisi laudantium? Repudiandae nesciunt, commodi error voluptatem. Quibusdam quisquam dolorem nostrum vero quis, voluptatum totam maiores! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dicta veniam dolor laudantium laboriosam exercitationem ducimus adipisci sapiente. Cum architecto, quaerat dolorum obcaecati quibusdam in non perspiciatis nisi hic dignissimos! Soluta?\n\n---\n\n##### Link ordered list:\n\n1. [Search for markdown](http://google.com/?q=markdown)\n2. [Markdown syntax](http://daringfireball.net/projects/markdown/syntax)\n\n##### Code block:\n\n\``function (a,b) {\nreturn a+b;\n}``\n\n##### Blockquote:\n> Generate static content with markdown in live preview!!";

			$scope.button_generator_demo = {
				button_size: '',
				button_type: 'btn-primary',
				button_disable: '',
				button_full: '',
				button_with_icon: '',
				button_text: 'Sample button text'
			}

			$scope.contentScrollbar = {
				autoResize: true,
				scrollbar: {  
					width: 2,
					hoverWidth: 2,
					color: '#65cac0',
					show: false
				},
				scrollbarContainer: {
					width: 4,
					color: '#e9f0f5'
				},
				scrollTo: null
			}

			$scope.multiselectDemo = [];
			$scope.multiselectDemoList = [
				{ name: 'test value 1', value: '1', selected: false },
				{ name: 'test value 2', value: '2', selected: false },
				{ name: 'test value 3', value: '3', selected: false },
				{ name: 'test value 4', value: '4', selected: false },
				{ name: 'test value 5', value: '5', selected: false },
				{ name: 'test value 6', value: '6', selected: false },
				{ name: 'test value 7', value: '7', selected: false },
				{ name: 'test value 8', value: '8', selected: false },
				{ name: 'test value 9', value: '9', selected: false },
				{ name: 'test value 10', value: '10', selected: false },
				{ name: 'test value 11', value: '11', selected: false }
			];

			$scope.selectDemo = [{
				value:"Gear",
				label:"Gear"
			},{
				value: "Globe",
				label:"Globe"
			},{
				value:"Heart",
				label:"Heart"
			},{
				value:"Camera",
				label:"Camera"
			}];

			$scope.autocompleteDemoList = ["Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Dakota","North Carolina","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming"];
			$scope.autocompleteSelect = '';

		}]);
})();