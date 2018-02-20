App.Front.UserProfile = function() {
    function listener() {
        Mapbox.setCenter([lng, lat]);
    };

    return {
        listener: listener
    };
}();
