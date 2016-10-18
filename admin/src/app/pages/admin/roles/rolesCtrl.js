(function() {
	'use strict';

	angular.module('Admin.pages.admin.roles')
		.controller('AdminRolesCtrl', AdminRolesCtrl);

	function AdminRolesCtrl($scope, $uibModal, toastr, Util, $state, $interval, MY) {
		console.log(MY)
		// var Collection, s = $scope;
		// Collection = MyResource.create('admin', 'roles');
		// s.select = function(i) {
		// 	Collection.query({
		// 		limit: 10,
		// 		offset: 10 * (i - 1)
		// 	}, function(d, headers) {
		// 		s.collections = d;
		// 		s.count = headers('x-total-count');
		// 		s.pages = Util.limiting(s.count, 10, i);
		// 	})
		// };
		// s.show = function(item) {
		// 	var config = {
		// 		animation: true,
		// 		templateUrl: 'app/pages/admin/roles/modalTemplates/show.html',
		// 		size: 'md',
		// 		controller: function($scope) {
		// 			$scope.item = item;
		// 			item.$get(function() {
		// 				$scope.state = Util.power($state.get(), item.power);
		// 			});
		// 			$scope.update = function() {
		// 				if (item.power !== '*') {
		// 					item.power = getPower($scope.state);
		// 				}
		// 				item.$update();
		// 				$scope.$close();
		// 			}
		// 		}
		// 	};
		// 	$uibModal.open(config).result.then(function() {
		// 		s.select(s.pages.select);
		// 	})
		// };
		// s.create = function() {
		// 	var config = {
		// 		animation: true,
		// 		templateUrl: 'app/pages/admin/roles/modalTemplates/create.html',
		// 		size: 'md',
		// 		controller: function($scope) {
		// 			var item = $scope.item = new Collection;
		// 			$scope.state = Util.power($state.get())
		// 			$scope.create = function() {
		// 				item.power = getPower($scope.state);
		// 				item.$save(function() {
		// 					$scope.$close();
		// 				});

		// 			}
		// 		}
		// 	};
		// 	$uibModal.open(config).result.then(function() {
		// 		s.select(1);
		// 	});
		// };
		// s.remove = function(item) {
		// 	var config = {
		// 		animation: true,
		// 		templateUrl: 'app/templates/remove.html',
		// 		size: 'sm'
		// 	};
		// 	$uibModal.open(config).result.then(function() {
		// 		item.$remove(function() {
		// 			$scope.select(1);
		// 		})
		// 	});

		// }
		// s.select(1);

		// function getPower(state) {
		// 	return state.filter(function(i) {
		// 		return i.value;
		// 	}).map(function(i) {
		// 		return i.title;
		// 	}).join(',');
		// }
	}
})();