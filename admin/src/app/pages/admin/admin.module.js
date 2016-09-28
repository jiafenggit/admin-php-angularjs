(function() {
  'use strict';

  angular.module('Admin.pages.admin', [
      'Admin.pages.admin.users',
      'Admin.pages.admin.roles'
    ])
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
      .state('admin', {
        url: '/admin',
        template: '<ui-view></ui-view>',
        abstract: true,
        title: '管理员中心',
        sidebarMeta: {
          icon: 'fa fa-book',
          order: 0,
        },
      });
  }

})();