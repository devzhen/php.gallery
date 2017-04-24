(function (module) {

    module.controller('AlbumListController', ["$scope", "$element", "$http", function ($scope, $element, $http) {

        $scope.deleteAlbum = function () {
            $scope.$broadcast("deleteAlbum");
        };

    }]);

})(angular.module('galleryApp'));