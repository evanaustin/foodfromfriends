App.Front = function() {
    mapboxgl.accessToken = 'pk.eyJ1IjoiZm9vZGZyb21mcmllbmRzIiwiYSI6ImNqN2twb2gwdTJmdWkzMm5wNmw0ejJ2cHEifQ.vv9p76S-5nm9ku_guP3-Pg';

    this.Mapbox = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v10',
        zoom: 13
    });

    // create & initialize a new instance of Slidebars
    this.Slidebar = new slidebars();
    Slidebar.init();

    function listener() {
        // make login direct back to current page
        $('form#log-in')
            .find('input[name="redirect"]')
            .val(false);

        $('#cart-toggle').on('click', function(e) {
            App.Util.slidebar(Slidebar, 'toggle', 'right', e);
        });

        $(Slidebar.events).on('opened', function () {
            $('a#cart-toggle').addClass('active');
        }).on('closed', function () {
            $('a#cart-toggle').removeClass('active');
        });

        /* $('body').on('click', '.close-any-slidebar', function(e) {
            e.stopPropagation();
            Slidebar.close();
        }); */

        $(document).keydown(function(e) {
            if (!($('input, textarea, select').is(':focus'))) {
                switch(e.keyCode) {
                    case 66: // b
                        App.Util.slidebar(Slidebar, 'toggle', 'right', e);
                        break;
                    case 68: // d
                        window.location.replace(PUBLIC_ROOT + 'dashboard/grower');
                        break;
                    case 77: // m
                        window.location.replace(PUBLIC_ROOT + 'map');
                }
            }
        });
    };

    return {
        Mapbox: this.Mapbox,
        Slidebar: this.Slidebar,
        listener: listener
    };
}();