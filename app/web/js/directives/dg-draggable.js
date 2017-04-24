(function (module) {

    module.directive('dgDraggable', ['$document', function ($document) {
        return {
            restrict: 'A',
            link: function ($scope, $element, $attrs) {
                var startX = 0, startY = 0, x = 0, y = 0, $clone;

                $element.on('mousedown', function (event) {

                    if (event.target.tagName === 'BUTTON') {
                        return;
                    }


                    // Prevent default dragging of selected content
                    event.preventDefault();

                    $clone = $element.clone(true);
                    document.body.appendChild($clone[0]);
                    $clone.css('position', 'absolute');
                    $clone.css('height', getComputedStyle($element[0]).height);
                    $clone.css('width', getComputedStyle($element[0]).width);
                    $clone.css('top', $element[0].offsetTop);
                    $clone.css('left', $element[0].offsetLeft);

                    $element.css('visibility','hidden');

                    startX = event.clientX - $element.offset().left;
                    startY = event.clientY - $element.offset().top;

                    $document.on('mousemove', mousemove);
                    $document.on('mouseup', mouseup);
                });

                function mousemove(event) {

                    y = event.clientY - startY;
                    x = event.clientX - startX;


                    $clone.offset({top: y, left: x});

                    checkPosition();
                }

                function mouseup(event) {

                    $element.css('visibility','visible');
                    $clone.remove();

                    $document.off('mousemove', mousemove);
                    $document.off('mouseup', mouseup);
                }


                function checkPosition() {

                }

            }
        }
    }]);

    function getCoords(elem) {

        var box = elem.getBoundingClientRect();

        var body = document.body;
        var docEl = document.documentElement;


        var scrollTop = window.pageYOffset || docEl.scrollTop || body.scrollTop;
        var scrollLeft = window.pageXOffset || docEl.scrollLeft || body.scrollLeft;


        var clientTop = docEl.clientTop || body.clientTop || 0;
        var clientLeft = docEl.clientLeft || body.clientLeft || 0;


        var top = box.top + scrollTop - clientTop;
        var left = box.left + scrollLeft - clientLeft;

        return {
            top: top,
            left: left,
            right: left + elem.offsetWidth,
            bottom: top + elem.offsetHeight
        };
    }
})(angular.module('galleryApp'));