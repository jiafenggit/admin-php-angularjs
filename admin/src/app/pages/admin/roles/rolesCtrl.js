(function() {
	'use strict';

	angular.module('Admin.pages.admin.roles')
		.controller('AdminRolesCtrl', AdminRolesCtrl);

	function AdminRolesCtrl($scope, $uibModal, toastr, Util, $state, $interval, MY) {
		var Collection, s = $scope;
		Collection = MY.$roles.resource;
		s.conf = {
			'$roles': MY.$roles.config
		}
		s.select = function(i) {
			Collection.query({
				limit: 10,
				offset: 10 * (i - 1),
				sort: 'id'
			}, function(d, headers) {
				s.collections = d;
				s.count = headers('x-total-count');
				s.pages = Util.limiting(s.count, 10, i);
			})
		};
		s.show = function(item) {
			$uibModal.open({
				animation: true,
				templateUrl: 'app/pages/admin/roles/modalTemplates/show.html',
				size: 'lg',
				controller: function($scope) {
					var s2, resourcies;
					s2 = $scope;
					s2.routerOption = {
						str: true,
						ready: false
					};
					s2.resOption = {
						ready: false
					}
					s2.item = item;
					item.$get(function() {
						s2.routerOption.data = item.router;
						s2.resOption.data = formatResStr(item.resource);
					});

					s2.update = function() {
						item.router = s2.routerOption.data;
						item.$update();
						$scope.$close();
					}
				}
			}).result.then(function() {
				s.select(s.pages.select);
			})
		};
		s.create = function() {
			var config = {
				animation: true,
				templateUrl: 'app/pages/admin/roles/modalTemplates/create.html',
				size: 'md',
				controller: function($scope) {
					var item = $scope.item = new Collection;
					$scope.state = Util.power($state.get())
					$scope.create = function() {
						item.power = getPower($scope.state);
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
		s.select(1);
		formatResStr();

		function formatResStr(str) {
			var def, o, info, obj;
			info = {}
			def = MY.$roles.info;
			angular.forEach(def, function(v) {
				if (typeof(info[v.controller]) === 'undefined') {
					info[v.controller] = {};
				}
				o = info[v.controller][v.resource] = {
					field: {},
					method: {}
				};
				v.xfield.split(',').map(function(s) {
					o.field[s] = true;
				});
				v.method.split(',').map(function(s) {
					o.method[s] = true;
				});
			})
			if (str !== '*') {
				obj = angular.json(str);
				disabled(o, info)
				des
			}
			return info
		}

		function disabled(o, info) {
          
		}
	}
})();