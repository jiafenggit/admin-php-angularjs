/**
 * Animated load block
 */
(function() {
  'use strict';

  angular.module('Admin.theme')
    .directive('editrow', editrow);

  /** @ngInject */
  function editrow() {
    return {
      restrict: 'A',
      require: '?ngModel',
      link: function($scope) {
       console.log($scope);
      }
    };
  }

})();