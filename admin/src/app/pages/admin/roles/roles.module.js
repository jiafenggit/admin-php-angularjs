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
        resolve: {
          _res: function(MyResource) {
            var Collection = MyResource.create('admin', 'roles');
            return Collection.info().$promise;
          },
          MY: function(MeResource, MyResource, _res) {
            var m = {};
            m.$roles = {
              resource: MyResource.create('admin', 'roles'),
              config: MeResource.resCtr('admin', 'roles', 'id,label,router,resource,utime,ctime'),
              info: _res
            };
            return m;
          }
        },
        sidebarMeta: {
          order: 1,
        },
      });
  }

})();