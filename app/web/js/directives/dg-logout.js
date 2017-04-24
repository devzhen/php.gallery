(function (module) {

    module.directive('dgLogout', ['$mdDialog', '$http', '$document', function ($mdDialog, $http, $document) {

        return {
            restrict: 'A',
            scope: false,
            link: function ($scope, $element, $attrs) {

                /*Удалить изображение*/
                $element.on('click', function (e) {

                    /*Диалог удаления изображения*/
                    var confirm = $mdDialog.confirm()
                        .title("Are you sure you want to log out?")
                        .ok('Ok')
                        .cancel('Cancel');

                    $mdDialog.show(confirm).then(function () {

                        /*Удалить изображение*/
                        $http({
                            method: 'POST',
                            url: '/logout'
                        }).then(function successCallback(response) {

                            window.location.href = response.data.redirect;

                        }, function errorCallback(response) {

                            window.location.reload();

                        });

                    }, function () {

                    });

                });
            }
        };

    }]);

})(angular.module('galleryApp'));