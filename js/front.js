App.Front = function() {
    mapboxgl.accessToken = 'pk.eyJ1IjoiZm9vZGZyb21mcmllbmRzIiwiYSI6ImNqN2twb2gwdTJmdWkzMm5wNmw0ejJ2cHEifQ.vv9p76S-5nm9ku_guP3-Pg';

    this.map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v10',
        zoom: 13
    });

    function listener() {
        // Create & initialize a new instance of Slidebars
        var controller = new slidebars();
        controller.init();

        $('#cart-toggle').on('click', function(e) {
            App.Util.slidebar(controller, 'toggle', 'right', e);
        });

        $('body').on('click', '.close-any-slidebar', function(e) {
            e.stopPropagation();
            controller.close();
        });
    };

    return {
        map: this.map,
        listener: listener
    };
}();