(function() {
  'use strict';

  angular.module('Admin.pages.home')
    .controller('homeCtrl', home);

  function home($scope,MeResource,$state) {
  	$scope.add =1;
  	$scope.ee = 12;
  }
})();