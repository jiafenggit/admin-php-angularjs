(function() {
  'use strict';

  angular.module('Admin.pages.home', [])
    .config(routeConfig);

  function routeConfig($stateProvider) {
    $stateProvider
      .state('home', {
        url: '/home',
        templateUrl: 'app/pages/home/home.html',
        title: '首页',
        sidebarMeta: {
          icon: 'ion-android-home',
          order: 0,
        },
      });
  }

})();