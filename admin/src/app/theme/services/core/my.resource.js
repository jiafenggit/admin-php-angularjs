'use strict';

(function() {
	function MyResource($resource, $state, $q, $timeout, toastr) {
		function create(controller, resource, key) {
			key = '@' + (key || 'id');
			return $resource('/api/admin/:controller/:resource/:id', {
				controller: controller,
				resource: resource,
				id: key,
			}, {
				'info': {
					method: 'GET',
					params: {
						id: 'info'
					},
					isArray: true
				},
				'save': {
					method: 'POST',
					transformResponse: [function(data, headersGetter, status) {
						if (status === 400) {
							angular.forEach(angular.fromJson(data), function(v) {
								toastr.warning(v, '警告');
							})
							return;
						} else if (status === 201) {
							toastr.info('创建成功');
						}

					}]
				},
				'remove': {
					method: 'DELETE',
					params: {
						id: key
					},
					transformResponse: [function(data, headersGetter, status) {
						if (status === 204) {
							toastr.success('删除成功');
						}

					}]
				},
				'update': {
					method: 'PUT',
					transformResponse: [function(data, headersGetter, status) {
						if (status === 204) {
							toastr.success('更新成功');
						}
						if (status === 400) {
							angular.forEach(angular.fromJson(data), function(v) {
								toastr.warning(v, '警告');
							})
						}
					}]
				}
			});
		};

		function load() {
			var d = $q.defer();
			$timeout(function() {
				d.resolve();
			}, 1000);
			return d.promise;
		};

		return {
			create: create,
			load: load,
		};
	}
	angular.module('Admin.theme.core')
		.factory('MyResource', MyResource);
})();