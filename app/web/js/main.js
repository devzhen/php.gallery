(function (module) {

    module.config(['$mdDateLocaleProvider', function ($mdDateLocaleProvider) {

        // Настройка datepicker
        $mdDateLocaleProvider.months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
        $mdDateLocaleProvider.shortMonths = ['Янв', 'Фев', 'Мрт', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Нбр', 'Дек'];
        $mdDateLocaleProvider.days = ['Воскрессенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'];
        $mdDateLocaleProvider.shortDays = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];

        $mdDateLocaleProvider.firstDayOfWeek = 1;

        // Начальная дата
        var today = new Date();
        today.setDate(today.getDate() - 364);
        $mdDateLocaleProvider.firstRenderableDate = today;

        // Конечная дата
        today = new Date();
        today.setDate(today.getDate() + 364);
        $mdDateLocaleProvider.lastRenderableDate = today;

        // Формат вывода даты
        $mdDateLocaleProvider.formatDate = function (date) {

            var dd = date.getDate();
            var mm = date.getMonth() + 1; //January is 0!

            var yyyy = date.getFullYear();
            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }

            return dd + '/' + mm + '/' + yyyy;
        };

    }]);

})(angular.module('galleryApp', ['ngMaterial']));
