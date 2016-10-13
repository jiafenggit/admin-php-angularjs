'use strict';

(function() {

	function MeResource($resource, $cookies, $q) {
		return {
			load: function() {
				return this.info = $resource('/api/admin/auth/info').get();
			},
			isAuthenticated: function(router) {
				if ("home,admin.users,admin.roles".split(',').indexOf(router) < 0) {
					return false;
				};
				return true;
			}

		};
	}

	angular.module('Admin.theme.core')
		.factory('MeResource', MeResource);

})();