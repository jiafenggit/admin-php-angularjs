/**
 * @author v.lugovksy
 * created on 15.12.2015
 */
(function() {
  'use strict';
  var i = 0;
  angular.module('Admin.theme')
    .run(themeRun);

  /** @ngInject */
  function themeRun($timeout, $rootScope, $q, $state, baSidebarService, MeResource, MyResource) {
    var whatToWait = [MeResource.load(), MyResource.load()];

    $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams, options) {
      if (MeResource.info.$promise.$$state.status === 0) {
        event.preventDefault();
        MeResource.info.$promise.then(function() {
          $state.go(toState);
        })
        return;
      }
      MeResource.isAuthenticated(toState.name) || event.preventDefault();
    });
    $q.all(whatToWait).then(function() {
      $rootScope.$pageFinishedLoading = true;
    });

    $timeout(function() {
      if (!$rootScope.$pageFinishedLoading) {
        $rootScope.$pageFinishedLoading = true;
      }
    }, 7000);

    $rootScope.$baSidebarService = baSidebarService;
  }

})();