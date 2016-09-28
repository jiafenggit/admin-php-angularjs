'use strict';

(function() {

	function MeResource($resource, $cookies, $q) {
		return {
			load: function() {
				return this.info = $resource('/api/admin/admin/info').get();
			},
			isAuthenticated: function(power, router) {
				if (power !== '*') {
					if (power.split(',').indexOf(router) < 0) {
						return false;
					};
				}
				return true;
			}

		};
	}

	angular.module('Admin.theme.core')
		.factory('MeResource', MeResource);

})();