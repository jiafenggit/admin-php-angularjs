'use strict';

(function() {

	function MeResource($resource, $cookies, $q, $state) {
		var defaultRouter, _routerArr;
		_routerArr = $state.get();
		defaultRouter = {};
		angular.forEach(_routerArr, function(value) {
			var statusArr, o;
			if (!value.name) return
			statusArr = value.name.split('.');
			o = defaultRouter;
			angular.forEach(statusArr, function(v) {
				if (typeof(o[v]) === "undefined") {
					o[v] = {};
				}
				o = o[v];
			})
		})
		return {
			defaults: {
				router: defaultRouter
			},
			load: function() {
				this.info = $resource('/api/admin/auth/info').get();
				return this.info.$promise;
			},
			isAuthenticated: function(routerStatus) {
				var r, o, obj, statusArr;
				r = this.info.role.router;
				if (r === '*') {
					return true;
				} else if (!r) {
					return false;
				} else {
					obj = angular.fromJson(r);
					statusArr = routerStatus.split('.');
					o = obj;
					for (var i = 0, l = statusArr.length; i < l; i++) {
						if (typeof(o[statusArr[i]]) === "undefined") {
							return false;
						}
						o = o[statusArr[i]];
					}
				}
				return true;
			},
			resCtr: function(ctr, res, fieldStr) {
				var result, fieldArr, ruleField, rs, rules, method, fieldObj = {};
				rs = this.info.role.resource;
				fieldArr = fieldStr.split(',');
				if (rs === '*') {
					angular.forEach(fieldArr, function(value) {
						fieldObj[value] = true;
					});
					return {
						method: {
							get: true,
							post: true,
							put: true,
							delete: true
						},
						field: fieldObj
					};
				} else {
					rules = angular.fromJson(rs);
					method = {
						get: false,
						post: false,
						put: false,
						delete: false
					};
					if (typeof(rules[ctr]) === "undefined") {
						return false
					} else if (typeof(rules[ctr][res]) === "undefined") {
						return false
					}
					angular.forEach(rules[ctr][res]['method'], function(value) {
						method[value] = true;
					});
					angular.forEach(fieldArr, function(value) {
						fieldObj[value] = false;
					});
					fieldArr = rules[ctr][res]['fields'].split(',');
					angular.forEach(fieldArr, function(value) {
						fieldObj[value] = true;
					});
					return {
						method: method,
						field: fieldObj
					};
				}
			}

		};
	}

	angular.module('Admin.theme.core')
		.factory('MeResource', MeResource);

})();