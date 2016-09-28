/**
 * @author v.lugovksy
 * created on 16.12.2015
 */
(function() {
  'use strict';

  angular.module('Admin.theme.components')
    .controller('BaSidebarCtrl', BaSidebarCtrl);

  /** @ngInject */
  function BaSidebarCtrl($scope, baSidebarService, MeResource) {
    var menu = baSidebarService.getMenuItems();
    // if (MeResource.info.role != 0) {
    //   menu.splice(1, 1);
    // };
    $scope.menuItems = menu;
    $scope.defaultSidebarState = $scope.menuItems[0].stateRef;

    $scope.hoverItem = function($event) {
      $scope.showHoverElem = true;
      $scope.hoverElemHeight = $event.currentTarget.clientHeight;
      var menuTopValue = 66;
      $scope.hoverElemTop = $event.currentTarget.getBoundingClientRect().top - menuTopValue;
    };

    $scope.$on('$stateChangeSuccess', function() {
      if (baSidebarService.canSidebarBeHidden()) {
        baSidebarService.setMenuCollapsed(true);
      }
    });
  }
})();