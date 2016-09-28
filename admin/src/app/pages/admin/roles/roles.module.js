(function() {
  'use strict';

  angular.module('Admin.pages.admin.roles', [])
    .config(routeConfig)
    .run(function($rootScope, MeResource, $state) {
      $rootScope.$on('$stateChangeStart', function(event, next) {
        MeResource.info.$promise.then(function(data) {
          MeResource.isAuthenticated(data.power, next.name) || $state.go('home');
        })

      });
    });;

  /** @ngInject */
  function routeConfig($stateProvider) {
    $stateProvider
      .state('admin.roles', {
        url: '/roles',
        templateUrl: 'app/pages/admin/roles/roles.html',
        title: '权限组',
        controller: 'AdminRolesCtrl',
        sidebarMeta: {
          order: 1,
        },
      });
  }

})();