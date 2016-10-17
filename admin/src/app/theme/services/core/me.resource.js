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
			}

		};
	}

	angular.module('Admin.theme.core')
		.factory('MeResource', MeResource);

})();