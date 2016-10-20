(function() {
  'use strict';

  angular.module('Admin.pages.admin.users', [])
    .config(routeConfig);

  function routeConfig($stateProvider) {
    $stateProvider
      .state('admin.users', {
        url: '/users',
        templateUrl: 'app/pages/admin/users/users.html',
        title: '管理员列表',
        resolve: {
          roles: function(MyResource,MeResource) {
            var roles = MyResource.create('admin', 'role');
            return new roles.query();
          }
        },
        controller: 'AdminUsersCtrl',
        sidebarMeta: {
          order: 2,
        },
      });
  }

})();