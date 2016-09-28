(function() {
  'use strict';

  angular.module('Admin.pages.admin.users', [])
    .config(routeConfig)
    .run(function($rootScope, MeResource, $state) {
      $rootScope.$on('$stateChangeStart', function(event, next) {
        MeResource.info.$promise.then(function(data) {
          MeResource.isAuthenticated(data.power, next.name) || $state.go('home');
        })

      });
    });

  function routeConfig($stateProvider) {
    $stateProvider
      .state('admin.users', {
        url: '/users',
        templateUrl: 'app/pages/admin/users/users.html',
        title: '管理员列表',
        resolve: {
          roles: function(MyResource) {
            var roles = MyResource.create('admin', 'role');
            return new roles.query();
          }
        },
        controller: 'AdminUsersCtrl',
        sidebarMeta: {
          order: 1,
        },
      });
  }

})();