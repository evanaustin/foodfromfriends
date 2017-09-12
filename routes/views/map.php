<!-- cont div.container-fluid -->
    <!-- cont div.row -->
        <!-- cont main -->
            <div class='main'>
                <div id='map'></div>
            </div>
        </div> <!-- end main -->
    </div> <!-- end div.row -->
</div> <!-- end div.container-fluid -->

<script>
    var growers = <?php echo json_encode($growers); ?>;
    var map;

    function initMap() {
        var harrisonburg = new google.maps.LatLng(38.4496, -78.8689);

        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: harrisonburg,
            mapTypeId: 'roadmap',
            styles: 
            [
                {
                    'featureType':'administrative',
                    'elementType':'all',
                    'stylers':[
                        {
                            'visibility':'on'
                        },
                        {
                            'lightness':33
                        }
                    ]
                },
                {
                    'featureType':'landscape',
                    'elementType':'all',
                    'stylers':[
                        {
                            'color':'#f0f0ee'
                        }
                    ]
                },
                {
                    'featureType':'poi',
                    'elementType':'labels',
                    'stylers':[
                        {
                            'visibility':'off'
                        }
                    ]
                },
                {
                    'featureType':'poi.park',
                    'elementType':'geometry',
                    'stylers':[
                        {
                            'color':'#c5dac6'
                        }
                    ]
                },
                {
                    'featureType':'poi.park',
                    'elementType':'labels',
                    'stylers':[
                        {
                            'visibility':'on'
                        },
                        {
                            'lightness':20
                        }
                    ]
                },
                {
                    'featureType':'road',
                    'elementType':'all',
                    'stylers':[
                        {
                            'lightness':20
                        }
                    ]
                },
                {
                    'featureType':'road.highway',
                    'elementType':'geometry',
                    'stylers':[
                        {
                            'color':'#c5c6c6'
                        }
                    ]
                },
                {
                    'featureType':'road.arterial',
                    'elementType':'geometry',
                    'stylers':[
                        {
                            'color':'#e4d7c6'
                        }
                    ]
                },
                {
                    'featureType':'road.local',
                    'elementType':'geometry',
                    'stylers':[
                        {
                            'color':'#fbfaf7'
                        }
                    ]
                },
                {
                    'featureType':'water',
                    'elementType':'all',
                    'stylers':[
                        {
                            'visibility':'on'
                        },
                        {
                            'color':'#acbcc9'
                        }
                    ]
                }
            ]
        });

        /* map.data.setStyle({
            icon: PUBLIC_ROOT + 'media/favicon-32.png/'
            // fillColor: 'green'
        }); */

        $.each(growers, function (i, grower) {
            addMarker(grower);
        });
    }

    function addMarker(grower) {
        var image = {
            // url: PUBLIC_ROOT + 'media/logos/favicon-32.png',
            url: 'https://s3.amazonaws.com/foodfromfriends/<?php echo ENV; ?>/profile-photos/' + grower.filename + '.' + grower.ext,
            // size: new google.maps.Size(100, 100),
            // origin: new google.maps.Point(0, 0),
            // anchor: new google.maps.Point(0, 25),
            scaledSize: new google.maps.Size(50, 50)
        };

        marker = new google.maps.Marker({
            position: new google.maps.LatLng(grower.latitude, grower.longitude),
            map: map,
            icon: image,
            title: grower.first_name,
            animation: google.maps.Animation.DROP,
            labelClass: grower
        });
    }

</script>

<script async defer
    src='https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPS_KEY; ?>&callback=initMap'>
</script>