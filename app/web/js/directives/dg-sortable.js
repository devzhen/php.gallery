(function (module) {

    module.directive('dgSortable', ['$http', function ($http) {

        return {
            restrict: 'A',
            scope: false,
            link: function ($scope, $element, $attrs) {

                var sortable = Sortable.create($element[0], {

                    onEnd: function (event) {

                        if ($attrs.dgSortable === '' || event.oldIndex === event.newIndex) {
                            return
                        }

                        /*Данные для отправки и url, указанные в post-запросе*/
                        var postData = null, urlSegment = null;

                        /*Если перемещение альбомов*/
                        if ($attrs.dgSortable === 'album') {

                            var albums = [];
                            for (var i = 0; i < $element[0].children.length; i++) {

                                var album = {
                                    id: $element[0].children[i].children[0].getAttribute('dg-album'),
                                    position: i
                                };

                                albums.push(album);
                            }

                            postData = albums;
                            urlSegment = '/update-position';

                            /*Если перемещение фотографий в альбоме*/
                        } else if ($attrs.dgSortable === 'image') {

                            var images = [];
                            for (var j = 0; j < $element[0].children.length; j++) {

                                var image = {
                                    src: $element[0].children[j].children[0].getAttribute('dg-image'),
                                    position: j
                                };

                                images.push(image);
                            }

                            postData = images;
                            urlSegment = '/update-image-position';

                        }

                        /*Отправка запроса*/
                        $http({
                            method: "POST",
                            url: '/admin/albums' + urlSegment,
                            data: postData
                        }).then(success, error);
                    }
                });

                function success(response) {

                }

                function error(response) {

                }
            }
        };

    }]);

})(angular.module('galleryApp'));