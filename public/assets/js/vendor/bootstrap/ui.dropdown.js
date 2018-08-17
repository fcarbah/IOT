(function () {
  'use strict';

  function btnDropDownDirectives ($document) {
    return {
      restrict: 'CA',
      link: function ($scope, $el, $attr) {
        var parent = $el[0].parentElement;

        $el.on('click', function (e) {
          e.preventDefault();

          // window right 
          if (e.view.outerWidth - e.clientX < 160  ) {
            parent.classList.toggle('right');
          }
          if( e.view.outerHeight - e.clientY < 160 ) {
            parent.classList.toggle('bottom');
          }

          parent.classList.toggle('open');
        });

        $document.bind('click', function (e) {
          if( e.target == $el[0] || e.target.parentNode === $el[0] ) { return; }
          parent.classList.remove('open','right','bottom');
        });
      }
    };
  }

  function btnLoaderDirective ($timeout) {
    return {
      restrict: 'A',
      scope: {
        title: '@loadingText',
        time: '@time'
      },
      link: function ($scope, $el, $attr) {
        var original = $el.text();
        var time = $scope.time ? $scope.time : 30;

        $el.on('click', function (e) {
          e.preventDefault();
          $el[0].disabled = true;
        });

        $el.bind('click', function (e) {
          $el.text($scope.title);         
          $timeout(function () {
            $el[0].disabled = false;
            $el.text(original);
          }, 1000 * time);
        });
      }
    };
  }

  angular
    .module('ui.dropdown', [])
    .directive('dropdownToggle', ['$document', btnDropDownDirectives])
    .directive('loadingText', ['$timeout', btnLoaderDirective]);
})();