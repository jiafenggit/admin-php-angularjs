(function() {
	'use strict';

	angular.module('Admin.theme')
		.filter('role', role);

	function role() {
		return function(input, Arr) {
			if (Arr instanceof Array) {
				var i, o, l = Arr.length;
				for (i = 0; i < l; i++) {
					o = Arr[i];
					if (+input === +o.id) {
						return o.label;
					}
				}
			}
			return '[异常]';
		};
	};

})();