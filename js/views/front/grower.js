App.Front.Grower = function() {
    function listener() {
        Mapbox.setCenter([lng, lat]);
    };

    return {
        listener: listener
    };
}();
