(function (module) {

    module.directive('dgImage', ['$mdDialog', '$http', '$document', function ($mdDialog, $http, $document) {

        return {
            restrict: 'A',
            scope: false,
            link: function ($scope, $element, $attrs) {

                /*Удалить изображение*/
                $scope.deleteImage = function (id, name) {


                    /*Диалог удаления изображения*/
                    var confirm = $mdDialog.confirm()
                        .title("Are you sure you want to delete the image '" + name + "'?")
                        .ok('Delete')
                        .cancel('Cancel');

                    $mdDialog.show(confirm).then(function () {

                        var data = new FormData();
                        data.append('imageId', id);

                        /*Удалить изображение*/
                        $http({
                            method: 'POST',
                            url: window.location.href + '/delete-image',
                            data: data,
                            headers: {'Content-Type': undefined}
                        }).then(function successCallback(response) {

                            window.location.reload();

                        }, function errorCallback(response) {

                        });

                    }, function () {

                    });

                };

                /**/
                $element.on('click', function (e) {

                    // Вывод изображения во весь экран
                    if (e.target.tagName === 'IMG' || e.target.tagName === 'MD-CARD-HEADER') {

                        var url = $attrs.dgImage;
                        if (!url || url === '') {
                            return;
                        }

                        $http({
                            method: 'GET',
                            url: url,
                            responseType: 'arraybuffer'
                        }).then(function successCallback(response) {

                            var blob = new Blob([response.data]);
                            var imgUrl = window.URL.createObjectURL(blob);

                            var $body = angular.element(document.body);

                            /*Серый фон*/
                            var modal_backgroung = angular.element('<div id="modal-app-background"></div>');
                            $body.append(modal_backgroung);
                            modal_backgroung.css({
                                'display': 'block',
                                'position': 'fixed',
                                'z-index': 999,
                                'pointer-events': 'none',
                                'left': 0,
                                'top': 0,
                                'width': '100%',
                                'height': '100%',
                                'background-color': 'rgba(0,0,0,0.9)'
                            });

                            /*Всплывающее изображение*/
                            var fancyImg = angular.element('<img id="fancyImg" src="' + imgUrl + '"/>');
                            $body.append(fancyImg);

                            fancyImg.on('load', function () {

                                var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
                                var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft;

                                fancyImg.css({
                                    'height': '100vh'
                                });

                                var left = document.documentElement.clientWidth / 2 + scrollLeft - fancyImg.outerWidth() / 2;
                                var top = document.documentElement.clientHeight / 2 + scrollTop - fancyImg.outerHeight() / 2;

                                fancyImg.css({
                                    'display': 'block',
                                    'position': 'absolute',
                                    'z-index': 1000,
                                    'border': '10px solid white',
                                    'left': left,
                                    'top': top
                                });

                                angular.element(window).on('resize', setPosition);
                                angular.element(window).on('scroll', setPosition);
                            });


                            $document.on('click', onmouseclick);

                            function setPosition(event) {

                                var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
                                var scrollLeft = window.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft;

                                var left = document.documentElement.clientWidth / 2 + scrollLeft - fancyImg.outerWidth() / 2;
                                var top = document.documentElement.clientHeight / 2 + scrollTop - fancyImg.outerHeight() / 2;

                                fancyImg.css({
                                    top: top,
                                    left: left
                                });
                            }

                            function onmouseclick(event) {
                                fancyImg.remove();
                                modal_backgroung.remove();
                                angular.element(window).off('resize', setPosition);
                                angular.element(window).off('scroll', setPosition);
                                $document.off('click', onmouseclick);
                            }
                        });
                    }
                });
            }
        };

    }]);

})(angular.module('galleryApp'));