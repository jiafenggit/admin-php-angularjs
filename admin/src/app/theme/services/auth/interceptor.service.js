(function() {
  'use strict';

  function authInterceptor($rootScope, $q, $cookies, $injector, Util) {
    return {
      // Add authorization token to headers
      request: function(config) {
        config.headers = config.headers || {};
        if ('PUT,POST'.indexOf(config.method) > -1) {

          config.headers['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
          config.transformRequest = [function(data) {
            var key, value, res = '';
            for (key in data) {
              value = data[key];
              if (key.indexOf('$') > -1 || angular.isFunction(value)) {
                continue;
              }
              res += param(key, value);
            }
            return res.length ? res.substr(0, res.length - 1) : res;
          }];

        }
        if ($cookies.get('token') && Util.isSameOrigin(config.url)) {
          config.headers.authorization = $cookies.get('token');
        }
        return config;

      },
      // Intercept 401s and redirect you to login
      responseError: function(response) {
        if (response.status === 401) {
          location.href = './auth.html';
          // $cookies.remove('token');
        }
        return $q.reject(response);
      }
    };
  }

  function param(key, value) {
    var fullSubName, subName, subValue, innerObj, i, l, res = '';
    if (value instanceof Array) {
      for (i = 0, l = value.length; i < l; ++i) {
        subValue = value[i];
        fullSubName = key + "[" + i + "]";
        innerObj = {};
        innerObj[fullSubName] = subValue;
        res += param(fullSubName, innerObj) + "&";
      }
    } else if (value instanceof Object) {
      for (subName in value) {
        subValue = value[subName];
        fullSubName = key + "[" + subName + "]";
        innerObj = {};
        innerObj[fullSubName] = subValue;
        res += param(fullSubName, innerObj) + "&";
      }
    } else if (value !== undefined && value !== null) {
      res += encodeURIComponent(key) + "=" + encodeURIComponent(value) + "&";
    }
    return res;
  }

  angular.module('Admin.theme.auth')
    .factory('authInterceptor', authInterceptor);

})();