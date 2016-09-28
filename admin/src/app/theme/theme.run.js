/**
 * @author v.lugovksy
 * created on 15.12.2015
 */
(function() {
  'use strict';

  angular.module('Admin.theme')
    .run(themeRun);

  /** @ngInject */
  function themeRun($timeout, $rootScope, $q, baSidebarService, MeResource, MyResource) {
    var whatToWait = [MeResource.load(), MyResource.load()];


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