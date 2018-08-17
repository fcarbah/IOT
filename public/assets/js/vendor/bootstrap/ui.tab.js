(function () {

  'use strict';
   
  angular
    .module('ui.tabs', [])
    .directive('navTabs', [function () {
      return {
        restrict: 'C',
        link: function (scope, el, attr) {
          var links = el[0].querySelectorAll('li a[href][data-toggle="tab"]');

          angular.element(links).on('click', function (e) {
            e.preventDefault();

            var pane = document.querySelector(this.hash);
            var parent = this.parentNode;
            
            [].forEach.call(pane.parentNode.querySelectorAll('.tab-pane.active'), function (item) {
              item.classList.remove('active');
            });

            [].forEach.call(parent.parentNode.querySelectorAll('li.active'), function (item) {
              item.classList.remove('active');
            });

            pane.classList.add('active');
            parent.classList.add('active');
          });
        }
      }
    }]);  

})();