App.Front.FoodListing = function() {
    function listener() {
        // App.Front.map.setZoom(13);
        App.Front.map.setCenter([lng, lat]);
        
        $('.btn-group button').on('click', function() {
            $('.btn-group button').removeClass('active');
            $(this).addClass('active');
        });
    };
    return {
        listener: listener
    };
}();