(function() {
  'use strict';

  angular.module('Admin.pages.admin.roles', [])
    .config(routeConfig);

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