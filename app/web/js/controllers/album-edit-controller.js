(function (module) {

    module.controller('AlbumEditController', ["$scope", "$element", "$http", function ($scope, $element, $http) {

        var self = this;

        this.album = {
            name: null,
            description:null
        };


        $http({
            method: "POST",
            url: window.location.href.replace("/edit", "")
        }).then(success, error);

        function success(responce) {

            self.album.name = responce.data.name;
            self.album.description = responce.data.description;

        }

        function error(responce) {

        }

        /*Обработчик отправки формы*/
        this.submitForm = function (event, form) {

            event.preventDefault();


            /*Отправка формы*/
            if (form.aName.$valid) {

                var albumForm = document.forms['albumForm'];
                albumForm.method = "POST";
                albumForm.action = window.location.href;
                albumForm.elements['aName'].value = albumForm.elements['aName'].value.substr(0, 25);
                albumForm.submit();
            }

        };

    }]);

})(angular.module('galleryApp'));