(function() {
	'use strict';

	angular.module('Admin.pages.home')
		.controller('homeCtrl', home);

	function home($scope, MeResource, $state) {
		$scope.data = {
			"name": "后台管理系统",
			"children": [{
				"name": "管理员中心",
				"children": [{
					"name": "管理员列表"
				}, {
					"name": "权限组"
				}]
			}, {
				"name": "首页"
			}]
		};
		$scope.status = true;
	}
})();