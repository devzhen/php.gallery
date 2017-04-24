(function (module) {

    module.directive('dgTempElement', ['$timeout', function ($timeout) {

        return {
            restrict: 'A',
            link: function ($scope, $element, $attrs) {

                var timeout = parseInt($attrs.dgTempElement) * 1000;

                if (isNaN(timeout)) {
                    timeout = 3000;
                }

                $timeout(function () {
                    $element.remove();
                }, timeout);
            }
        };

    }]);

})(angular.module('galleryApp'));