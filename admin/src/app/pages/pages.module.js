(function() {
  'use strict';

  angular.module('Admin.pages', [
      'ui.router',

      'Admin.pages.home',
      'Admin.pages.admin',
    ]);
  //   .config(routeConfig);

  // function routeConfig($urlRouterProvider, baSidebarServiceProvider) {
  //   $urlRouterProvider.otherwise('/home');

  // }

})();