(function() {
  'use strict';
  angular.module('Admin.theme.auth', [
    'Admin.theme.util',
    'ngCookies',
    'ui.router'
  ]).config(function($httpProvider) {
    $httpProvider.interceptors.push('authInterceptor');
  });

})();