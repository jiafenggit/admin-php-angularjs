'use strict';

(function() {

	function MeResource($resource, $cookies, $state, $filter, Util) {
		return {
			defaults: {
				router: Util.formatRouter($state.get())
			},
			load: function() {
				this.info = $resource('/api/admin/auth/info').get();
				return this.info.$promise;
			},
			isAuthenticated: function(routerStatus) {
				var r, arr, l, selected, router;
				r = this.info.role.router;
				r = '[{"name":"home","parent":"root","level":"0"}]';
				if (r === '*') {
					return true;
				}
				router = angular.fromJson(r);
				arr = routerStatus.split('.');
				l = arr.length - 1;
				selected = $filter('filter')(router, {
					level: l,
					name: arr[l],
					parent: l === 0 ? 'root' : arr[l - 1]
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