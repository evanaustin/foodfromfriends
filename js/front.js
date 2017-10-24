$('.js-open-right-slidebar').on('click', function(event) {
    event.stopPropagation();
    controller.open('slidebar-2');
});

$('.js-close-right-slidebar').on('click', function(event) {
    event.stopPropagation();
    controller.close('slidebar-2');
});

$('.js-toggle-right-slidebar').on('click', function(event) {
    event.stopPropagation();
    controller.toggle('slidebar-2');
});