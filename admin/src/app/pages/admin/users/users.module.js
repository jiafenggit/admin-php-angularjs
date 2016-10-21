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
          MY: function(MeResource, MyResource) {
            console.log(MeResource, MyResource);
            var m = {};
            m.$roles = {
              resource: MyResource.create('admin', 'roles'),
              config: MeResource.resCtr('admin', 'roles', 'id,label,router,resource,utime,ctime'),
              info: _res
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