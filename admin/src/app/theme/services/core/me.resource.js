'use strict';

(function() {

	function MeResource($resource, $cookies, $state, $filter, Util) {
		return {
			defaults: {
				router: Util.formatRouter($state.get())
			},
			load: function() {
				var s = this;
				s.info = $resource('/api/admin/auth/info').get(function(d) {
					if (d.role.router === '*') {
						d.role.router = s.defaults.router;
					} else {
						d.role.router = Util.formatRouterObj(angular.fromJson(d.role.router));
					}
				});
				return this.info.$promise;
			},
			isAuthenticated: function(routerStatus) {
				var arr, i, selected, router;
				router = this.info.role.router;
				arr = routerStatus.split('.');
				i = arr.length - 1;
				selected = $filter('filter')(router, {
					level: i,
					name: arr[i],
					parent: i === 0 ? 'root' : arr[i - 1]
				});
				return selected.length > 0 ? true : false;
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