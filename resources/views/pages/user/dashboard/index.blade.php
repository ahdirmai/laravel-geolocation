@extends('layouts.app')

@section('content')
@push('style')
<style>
    #map {
        height: 500px;
    }
</style>
@endpush


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif


                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('script')
<script src="https://code.jquery.com/jquery-3.6.3.min.js"
    integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
    integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
    crossorigin=""></script>
<script>
    setTimeout(function () { map.invalidateSize() }, 800);



    // Menambah attribut pada leaflet
        var mbAttr = 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
            'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            mbUrl =
            'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';
        // membuat beberapa layer untuk tampilan map diantaranya satelit, dark mode, street
            var satellite = L.tileLayer(mbUrl, {
                id: 'mapbox/satellite-v9',
                tileSize: 512,
                zoomOffset: -1,
                attribution: mbAttr
            }),
            dark = L.tileLayer(mbUrl, {
                id: 'mapbox/dark-v10',
                tileSize: 512,
                zoomOffset: -1,
                attribution: mbAttr
            }),
            streets = L.tileLayer(mbUrl, {
                id: 'mapbox/streets-v11',
                tileSize: 512,
                zoomOffset: -1,
                attribution: mbAttr
            });


        // Membuat var map untuk instance object map ke dalam tag div yang mempunyai id map
        // menambahkan titik koordinat latitude dan longitude peta indonesia kedalam opsi center
        // mengatur zoom map dan mengatur layer yang akan digunakan
        var map = L.map('map', {
            center: [-3.31987,114.59075],
            zoom: 12,
            autoPan: false,
            layers: [streets]
        });


        var baseLayers = {
            //"Grayscale": grayscale,
            "Streets": streets,
            "Satellite" : satellite
        };
        var overlays = {
            "Streets": streets,
            "Satellite": satellite,
        };

        //Menambahkan beberapa layer ke dalam peta/map
        L.control.layers(baseLayers, overlays).addTo(map);


        // set current location / lokasi sekarang dengan koordinat peta indonesia
        var curLocation = [-3.31987,114.59075];
        map.attributionControl.setPrefix(false);



        // set marker map agar bisa di geser
        // Set Market Unit
        var markerUnit = new L.marker({
            lat:{{ $unit->latitude }},lon:{{ $unit->longitude }}
        }, {
            draggable: 'false',
        }).bindPopup('Unit Location');
        map.addLayer(markerUnit);



        var circleUnit = new L.circle({
            lat:{{ $unit->latitude }},lon:{{ $unit->longitude }}
        },500);
        map.addLayer(circleUnit);

        if (!navigator.geolocation) {
            console.log("Your browser doesn't support geolocation feature!")
        } else {
            setInterval(() => {
                navigator.geolocation.getCurrentPosition(getPosition)
            }, 3000);
        };


        var userMarker, userCircle, userLat, userLong, userAccuracy;

        function getPosition(position) {
            // console.log(position)
            userLat = position.coords.latitude
            userLong = position.coords.longitude
            userAccuracy = position.coords.accuracy

            if (userMarker) {
                map.removeLayer(userMarker)
            }

            if (userCircle) {
                map.removeLayer(userCircle)
            }

            userMarker = L.marker([userLat, userLong]).bindPopup('User Location')
            userCircle = L.circle([userLat, userLong], {
                radius: userAccuracy
            })

            var featureGroup = L.featureGroup([userMarker, userCircle]).addTo(map)
            map.fitBounds(featureGroup.getBounds())
            map.setZoom(12)
            console.log("Your coordinate is: Lat: " + userLat + " Long: " + userLong + " Accuracy: " + userAccuracy)
            var userLocation = [userLat,userLong]
            getDistance(userLocation);

        }

        function getDistance (userLocation) {
            console.log({{ $unit->latitude }},{{ $unit->longitude }});
            var unit = [{{ $unit->latitude }},{{ $unit->longitude }}];
            var user = userLocation
            var distance = map.distance(user,unit)/1000
            console.log(distance);
            var polyline = L.polyline([unit,user], {color: 'red'}).bindPopup('distance is : '+distance + ' km' ).addTo(map);

            polyline.setStyle({
                color: 'black',
                weight :2,
                dashArray:'30'
            });
        }



</script>

@endpush

@endsection
