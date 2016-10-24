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
          USERS_INFO: function(MyResource) {
            var Collection = MyResource.create('admin', 'users');
            return Collection.info().$promise;
          },
          MY: function(MeResource, MyResource, USERS_INFO) {
            var m = {};
            m.$users = {
              resource: MyResource.create('admin', 'users', 'uid'),
              config: MeResource.resCtr('admin', 'users', 'uid,username,name,role,ip,utime,ctime'),
              info: {
                roles: USERS_INFO
              }
            };
            return m;
          }
        },
        controller: 'AdminUsersCtrl',
        sidebarMeta: {
          order: 2,
        },
      });
  }

})();