mapboxgl.accessToken = 'pk.eyJ1IjoiZm9vZGZyb21mcmllbmRzIiwiYSI6ImNqN2twb2gwdTJmdWkzMm5wNmw0ejJ2cHEifQ.vv9p76S-5nm9ku_guP3-Pg';

var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v10',
    center: [lng, lat],
    zoom: 13
});