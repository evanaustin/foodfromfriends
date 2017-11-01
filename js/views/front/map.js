App.Front.Map = function() {
    function listener() {
        // set center to Harrisonburg
        Mapbox.setCenter([-78.8689, 38.4496]);

        // initialize marker array
        var markers = [];

        // load mapbox
        Mapbox.on('load', function() {
            Mapbox.addSource('growers', {
                type: 'geojson',
                data: data,
                cluster: true,
                clusterMaxZoom: 14, // Max zoom to cluster points on
                clusterRadius: 14 // Radius of each cluster when clustering points (defaults to 50)
            });

            Mapbox.addLayer({
                id: 'clusters',
                type: 'circle',
                source: 'growers',
                filter: ['has', 'point_count'],
                paint: {
                    'circle-color': {
                        property: 'point_count',
                        type: 'interval',
                        stops: [
                            [0, '#3FC592'],
                            [5, '#00A669'],
                            [10, '#005434'],
                        ]
                    },
                    'circle-radius': {
                        property: 'point_count',
                        type: 'interval',
                        stops: [
                            [0, 20],
                            [5, 30],
                            [10, 40]
                        ]
                    }
                }
            });

            Mapbox.addLayer({
                id: 'cluster-count',
                type: 'symbol',
                source: 'growers',
                filter: ['has', 'point_count'],
                layout: {
                    'text-field': '{point_count_abbreviated}',
                    'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
                    'text-size': 12
                },
                paint : {
                    'text-color': '#fff',
                }
            });

            Mapbox.addLayer({
                id: 'unclustered-point',
                type: 'circle',
                source: 'growers',
                filter: ['!has', 'point_count'],
                paint: {
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
                    .addTo(Mapbox);

                markers.push(marker);
            });

            var popup = new mapboxgl.Popup({
                closeButton: false,
                closeOnClick: false
            });

            // hide marker pulse by default
            // $('.mapboxgl-marker').addClass('hidden');

            // show marker pulse after auto zoom ends
            /* Mapbox.flyTo({zoom: 13}, function() {
                $('.mapboxgl-marker').removeClass('hidden');
            }); */
        });

        Mapbox.on('mouseover', 'unclustered-point', function (e) {
            var html = '<div class="grower-profile"' +
                            'style="background-image:url(' + e.features[0].properties.photo + ');">' +
                        '</div>' +
                        '<div class="info">' +
                            '<div class="title">' +
                                '<div class="name">' +
                                    e.features[0].properties.name +
                                '</div>' +
                                /* '<div class="rating">' +
                                    e.features[0].properties.rating +
                                '</div>' + */
                            '</div>' +
                            '<div class="distance">' +
                                e.features[0].properties.distance +
                            '</div>' +
                            '<div class="listings">' +
                                e.features[0].properties.listings +
                            '</div>' +
                        '</div>';

            popup.setLngLat(e.features[0].geometry.coordinates)
                .setHTML(html)
                .addTo(Mapbox);
        });

        Mapbox.on('mouseleave', 'unclustered-point', function() {
            popup.remove();
        });

        // show/hide marker pulse 
        Mapbox.on('zoomend', function (e) {
            if (Mapbox.getZoom() < 13) {
                $('.mapboxgl-marker').addClass('hidden');
            } else if (Mapbox.getZoom() >= 13) {
                $('.mapboxgl-marker').removeClass('hidden');
            }
        });
    };

    return {
        listener: listener
    };
}();