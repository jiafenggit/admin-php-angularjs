(function() {
	'use strict';

	angular.module('Admin.pages.admin.users')
		.controller('AdminUsersCtrl', AdminUsersCtrl);

	function AdminUsersCtrl($scope, $http, $uibModal, toastr, Util, roles, MyResource) {
		var Collection, s = $scope;
		Collection = MyResource.create('admin', 'users', 'uid');
		s.roles = roles;
		s.select = function(i) {
			Collection.query({
				limit: 10,
				offset: 10 * (i - 1)
			}, function(d, headers) {
				s.collections = d;
				s.count = headers('x-total-count');
				s.pages = Util.limiting(s.count, 10, i);
			})
		};
		s.show = function(item) {
			var config = {
				animation: true,
				templateUrl: 'app/pages/admin/users/modalTemplates/show.html',
				size: 'md',
				controller: function($scope) {
					$scope.roles = roles;
					$scope.item = item;
					item.$get();
					$scope.update = function() {
						item.$update();
						$scope.$close();
					}
				}
			};
			$uibModal.open(config).result.then(function() {
				s.select(s.pages.select);
			})
		};
		s.create = function() {
			var config = {
				animation: true,
				templateUrl: 'app/pages/admin/users/modalTemplates/create.html',
				size: 'md',
				controller: function($scope) {
					var item = $scope.item = new Collection({
						role: roles[0].id
					});
					$scope.roles = roles;
					$scope.create = function() {
						item.$save(function() {
							$scope.$close();
						});

					}
				}
			};
			$uibModal.open(config).result.then(function() {
				s.select(1);
			});
		};
		s.remove = function(item) {
			var config = {
				animation: true,
				templateUrl: 'app/templates/remove.html',
				size: 'sm'
			};
			$uibModal.open(config).result.then(function() {
				item.$remove(function() {
					$scope.select(1);
				})
			});

		}
		$scope.select(1);
	}
})();