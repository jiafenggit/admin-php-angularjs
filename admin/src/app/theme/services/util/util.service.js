'use strict';

(function() {

  angular.module('Admin.theme.util')
    .factory('Util', UtilService);

  /**
   * The Util service is for thin, globally reusable, utility functions
   */
  function UtilService($window) {
    var Util = {
      /**
       * Return a callback or noop function
       *
       * @param  {Function|*} cb - a 'potential' function
       * @return {Function}
       */
      safeCb: function(cb) {
        return (angular.isFunction(cb)) ? cb : angular.noop;
      },

      /**
       * Parse a given url with the use of an anchor element
       *
       * @param  {String} url - the url to parse
       * @return {Object}     - the parsed url, anchor element
       */
      urlParse: function(url) {
        var a = document.createElement('a');
        a.href = url;
        return a;
      },

      /**
       * Test whether or not a given url is same origin
       *
       * @param  {String}           url       - url to test
       * @param  {String|String[]}  [origins] - additional origins to test against
       * @return {Boolean}                    - true if url is same origin
       */
      isSameOrigin: function(url, origins) {
        url = Util.urlParse(url);
        origins = (origins && [].concat(origins)) || [];
        origins = origins.map(Util.urlParse);
        origins.push($window.location);
        origins = origins.filter(function(o) {
          return url.hostname === o.hostname &&
            url.port === o.port &&
            url.protocol === o.protocol;
        });
        return (origins.length >= 1);
      },
      enumInt: function(number) {
        var res = [];
        for (var i = 0; i < number; i++) {
          res[i] = i + 1;
        }
        return res;
      },
      limiting: function(count, limit, select) {
        var pages, start, list, i;
        pages = Math.ceil(count / 10);
        if (pages < limit) {
          start = 1;
          limit = pages;
        } else {
          start = (select + limit / 2) > pages ?
            pages - limit + 1 :
            select > limit / 2 ? select - limit / 2 : 1;
        }
        for (i = 0, list = []; i < limit; i++) {
          list[i] = i + start;
        }
        return {
          count: pages,
          list: list,
          select: select
        };
      },
      isEmptyObject: isEmptyObject,
      formatRouterObj: function(obj, parent, level) {
        var res, item, s;
        res = [];
        s = this;
        level = level || 0;
        parent = parent || 'root';
        angular.forEach(obj, function(v, k) {
          res.push({
            name: k,
            parent: parent,
            level: level
          });
          if (!isEmptyObject(v)) {
            item = s.formatRouterObj(v, k, level + 1);
            res = res.concat(item);
          }
        })
        return res;
      },
      formatRouter: function(state) {
        var d = [];

        angular.forEach(state, function(value) {
          var arr, l;
          if (!value.name) return
          arr = value.name.split('.');
          l = arr.length - 1;
          d.push({
            name: arr[l],
            parent: l === 0 ? 'root' : arr[l - 1],
            title: value.title,
            level: l
          })
        })

        return d;
      }
    };

    return Util;
  }

  function isEmptyObject(e) {
    var t;
    for (t in e)
      return !1;
    return !0
  }
})();