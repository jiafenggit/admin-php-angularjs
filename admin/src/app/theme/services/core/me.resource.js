'use strict';

(function() {

	function MeResource($resource, $cookies, $q) {
		return {
			load: function() {
				this.info = $resource('/api/admin/auth/info').get();
				return this.info.$promise;
			},
			isAuthenticated: function(router) {
				var r = this.info.role.router;
				if (r === '*') {
					return true;
				}
				if (r.split(',').indexOf(router) < 0) {
					return false;
				};
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