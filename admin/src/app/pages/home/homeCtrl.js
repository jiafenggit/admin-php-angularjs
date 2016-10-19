(function() {
  'use strict';

  angular.module('Admin.pages.home')
    .controller('homeCtrl', home);

  function home($scope,MeResource,$state) {
  	console.log($state.get());
  	$scope.add =1;
  	$scope.ee = 12;
  }
})();