mapboxgl.accessToken = 'pk.eyJ1IjoiZm9vZGZyb21mcmllbmRzIiwiYSI6ImNqN2twb2gwdTJmdWkzMm5wNmw0ejJ2cHEifQ.vv9p76S-5nm9ku_guP3-Pg';

var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v10',
    center: [-78.8689, 38.4496], // Harrisonburg
    zoom: 13
});

var markers = [];

map.on('load', function() {
    map.addSource('growers', {
        type: 'geojson',
        data: data,
        cluster: true,
        clusterMaxZoom: 14, // Max zoom to cluster points on
        clusterRadius: 50 // Radius of each cluster when clustering points (defaults to 50)
    });

    map.addLayer({
        id: 'clusters',
        type: 'circle',
        source: 'growers',
        filter: ['has', 'point_count'],
        paint: {
            'circle-color': {
                property: 'point_count',
                type: 'interval',
                stops: [
                    [0, '#51bbd6'],
                    [10, '#f1f075'],
                    [50, '#f28cb1'],
                ]
            },
            'circle-radius': {
                property: 'point_count',
                type: 'interval',
                stops: [
                    [0, 20],
                    [10, 30],
                    [50, 40]
                ]
            }
        }
    });

    map.addLayer({
        id: 'cluster-count',
        type: 'symbol',
        source: 'growers',
        filter: ['has', 'point_count'],
        layout: {
            'text-field': '{point_count_abbreviated}',
            'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
            'text-size': 12
        }
    });

    map.addLayer({
        id: 'unclustered-point',
        type: 'circle',
        source: 'growers',
        filter: ['!has', 'point_count'],
        paint: {
            // 'circle-color': '#11b4da',
            'circle-color': '#3FC592',
            'circle-radius': 7,
            'circle-stroke-width': 2,
            'circle-stroke-color': '#fff'
        }
    });

    data.features.forEach(function(marker) {
        var el = document.createElement('div');
        el.className = 'pulse';

        // make a marker for each feature and add to the map
        var marker = new mapboxgl.Marker(el, { offset: [0, 0] })
            .setLngLat(marker.geometry.coordinates)
            .addTo(map);

        markers.push(marker);
    });

    console.log(map.getZoom());

    // When a click event occurs on a feature in the places layer, open a popup at the
    // location of the feature, with description HTML from its properties.
    map.on('click', 'unclustered-point', function (e) {
        var html = '<div class="grower-profile"' +
                        'style="background-image:url(' + e.features[0].properties.photo + ');">' +
                    '</div>' +
                    '<div class="info">' +
                        '<div class="title">' +
                            '<div class="name">' +
                                e.features[0].properties.name +
                            '</div>' +
                            '<div class="rating">' +
                                e.features[0].properties.rating +
                            '</div>' +
                        '</div>' +
                        '<div class="listings">' +
                            e.features[0].properties.foods +
                        '</div>' +
                    '</div>';

        new mapboxgl.Popup()
            .setLngLat(e.features[0].geometry.coordinates)
            .setHTML(html)
            .addTo(map);
    });
});

map.on('zoomend', function (e) {
    if (map.getZoom() < 12) {
        $('.mapboxgl-marker').addClass('hidden');
    } else if (map.getZoom() >= 12) {
        $('.mapboxgl-marker').removeClass('hidden');
    }
});  