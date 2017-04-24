(function (module) {

    module.directive('dgAlbum', ['$mdDialog', '$http', function ($mdDialog, $http) {

        return {
            restrict: 'A',
            scope: true,
            link: function ($scope, $element, $attrs) {

                // Получение id альбома
                $scope.id = $attrs.dgAlbum;

                $element.on('click', function (e) {

                    // Удалить альбом
                    if (e.target.tagName === 'IMG' && e.target.classList.contains('delete')) {

                        /*Диалог удаления альбома*/
                        var confirm = $mdDialog.confirm()
                            .title('Are you sure you want to delete the album?')
                            .ok('Delete')
                            .cancel('Cancel');

                        $mdDialog.show(confirm).then(function () {


                            var data = new FormData();
                            data.append('albumId', $scope.id);

                            /*Удалить альбом*/
                            $http({
                                method: 'POST',
                                url: '/admin/albums/delete',
                                data: data,
                                headers: {'Content-Type': undefined}
                            }).then(function successCallback(response) {

                                window.location.reload();

                            }, function errorCallback(response) {

                            });

                            /*Спрятать кнопку удаления альбома*/
                            $element.parent().scope().$broadcast("deleteAlbum");

                        }, function () {
                            /*Если выход - спрятать кнопку удаления альбома*/
                            $element.parent().scope().$broadcast("deleteAlbum");
                        });

                    }
                });

                /*Перехват события - удалить альбом - отобразить кнопку удаления*/
                $scope.$on('deleteAlbum', function (e) {

                    $element.find('.album-details').toggleClass('display-off');
                    $element.find('.deleteAlbum').toggleClass('display-off');

                });

            }
        };

    }]);

})(angular.module('galleryApp'));