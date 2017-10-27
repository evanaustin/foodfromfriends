App.Front.Grower = function() {
    function listener() {
        App.Front.map.setCenter([lng, lat]);
    };

    return {
        listener: listener
    };
}();
