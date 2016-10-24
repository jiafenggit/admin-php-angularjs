(function() {
	'use strict';

	angular.module('Admin.pages.admin.users')
		.controller('AdminUsersCtrl', AdminUsersCtrl);

	function AdminUsersCtrl($scope, $uibModal, toastr, Util, MY) {
		var Collection, s = $scope;
		Collection = MY.$users.resource;
		s.conf = {
			'$users': MY.$users.config
		};
		s.roles = MY.$users.info.roles;
		s.select = function(i) {
			Collection.query({
				limit: 10,
				offset: 10 * (i - 1),
				sort: 'uid'
			}, function(d, headers) {
				s.collections = d;
				s.count = headers('x-total-count');
				s.pages = Util.limiting(s.count, 10, i);
			})
		};
		s.show = function(item) {
			$uibModal.open({
				animation: true,
				templateUrl: 'app/pages/admin/users/modalTemplates/show.html',
				size: 'md',
				controller: function($scope) {
					var s2 = $scope;
					s2.conf = s.conf.$users;
					s2.roles = s.roles;
					s2.item = item;
					item.$get();
					$scope.update = function() {
						item.$update();
						s2.$close();
					}
				}
			}).result.then(function() {
				s.select(s.pages.select);
			})
		};
		s.create = function() {
			$uibModal.open({
				animation: true,
				templateUrl: 'app/pages/admin/users/modalTemplates/create.html',
				size: 'md',
				controller: function($scope) {
					var s2, item;
					s2 = $scope;
					s2.roles = s.roles;
					s2.item = item = new Collection({
						role: s2.roles[0].id
					});
					s2.create = function() {
						item.$save(function() {
							s2.$close();
						});

					}
				}
			}).result.then(function() {
				s.select(1);
			});
		};
		s.remove = function(item) {
			$uibModal.open({
				animation: true,
				templateUrl: 'app/templates/remove.html',
				size: 'sm'
			}).result.then(function() {
				item.$remove(function() {
					s.select(1);
				})
			});

		};

		s.select(1);
	}
})();