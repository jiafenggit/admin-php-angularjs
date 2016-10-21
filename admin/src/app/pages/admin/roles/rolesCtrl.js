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
					};
					s2.resOption = {}
					s2.item = item;
					item.$get(function() {
						s2.routerOption.data = item.router;
						s2.resOption.data = formatStrRes(item.resource);
					});

					s2.update = function() {
						if (item.id !== 1) {
							item.router = s2.routerOption.data;
							item.resource = formatResStr(s2.resOption.data)
						}
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


		function formatStrRes(str) {
			var conf, obj, result = {};
			conf = {
				data: MY.$roles.info,
				status: str === '*'
			};
			angular.forEach(conf.data, function(v) {
				if (typeof(result[v.controller]) === 'undefined') {
					result[v.controller] = {
						$$disabled: !conf.status
					};
				}
				result[v.controller][v.resource] = {
					fields: {},
					method: {},
					$$disabled: !conf.status
				};
				v.xfield.split(',').map(function(s) {
					result[v.controller][v.resource].fields[s] = conf.status;
				});
				v.method.split(',').map(function(s) {
					result[v.controller][v.resource].method[s] = conf.status;
				});
			})
			if (!conf.status) {
				obj = angular.fromJson(str);
				angular.forEach(obj, function(value, ctr) {
					if (typeof(result[ctr]) === "object") {
						result[ctr].$$disabled = false;
						angular.forEach(obj[ctr], function(v, rest) {
							if (typeof(result[ctr][rest]) === "object") {
								result[ctr][rest].$$disabled = false;
								disabled(result[ctr][rest], 'fields', v.fields.split(','))
								disabled(result[ctr][rest], 'method', v.method)
							}

						})
					}
				})
			}
			return result;
		}

		function formatResStr(obj) {
			var result = {};
			angular.forEach(obj, function(value, ctr) {
				if (value.$$disabled) return;
				result[ctr] = {};
				angular.forEach(value, function(v, rest) {
					if (rest.indexOf('$') > -1) return
					if (v.$$disabled) return;
					result[ctr][rest] = {
						fields: translateObjStr(v.fields),
						method: translateObjStr(v.method).split(',')
					}
				})
			})
			return angular.toJson(result);
		}

		function translateObjStr(obj) {
			var result = '';
			angular.forEach(obj, function(v, k) {
				if (v) result += k + ',';
			})
			return result.length > 0 ? result.substr(0, result.length - 1) : '';
		}

		function disabled(obj, key, arr) {
			angular.forEach(obj[key], function(v, k) {
				if (arr.indexOf(k) > -1) {
					obj[key][k] = true;
				}
			})
		}
	}
})();