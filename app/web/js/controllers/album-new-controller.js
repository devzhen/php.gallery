(function (module) {

    module.controller('AlbumNewController', ["$scope", "$element", function ($scope, $element) {

        this.newAlbum = {
            name: null,
            unixDate: null,
            timezoneOffset: null
        };

        /*Дата создания альбома*/
        this.creationDate = new Date();

        /*Получение смещения временной зоны на клиенте*/
        var timezone = -this.creationDate.getTimezoneOffset() / 60;
        var offset = '';
        if (timezone > 0) {
            offset += "+";
        } else {
            offset += "-";
        }
        if (Math.abs(timezone) < 10) {
            offset += "0";
        }
        offset += timezone;
        offset += "00";
        this.newAlbum.timezoneOffset = offset;


        /*Обработчик выбора даты в date picker*/
        this.dateChanged = function () {

            /*Получение Unix временной отметки*/
            this.newAlbum.unixDate = '@' + Math.floor(+this.creationDate / 1000);
        };

        /*Обработчик отправки формы*/
        this.submitForm = function (event, form) {

            event.preventDefault();


            /*Коррекция времени создания альбома*/
            var now = new Date();
            this.creationDate.setHours(now.getHours());
            this.creationDate.setMinutes(now.getMinutes());
            this.creationDate.setSeconds(now.getSeconds());

            /*Получение Unix временной отметки*/
            this.newAlbum.unixDate = '@' + Math.floor(+this.creationDate / 1000);


            /*Отправка формы*/
            if (form.aName.$valid) {

                var albumForm = document.forms['albumForm'];
                albumForm.method = "POST";
                albumForm.action = "/admin/albums/new";
                albumForm.elements['aName'].value = albumForm.elements['aName'].value.substr(0, 25);
                albumForm.elements['aDate'].value = this.newAlbum.unixDate;
                albumForm.elements['aDate'].value = this.newAlbum.unixDate;
                albumForm.submit();
            }

        };

    }]);

})(angular.module('galleryApp'));