@extends('layouts.app')

@section('content')
@push('style')
<style>
    #map {
        height: 300px;
    }
</style>
@endpush
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Units') }}</div>
                <div class="card-body">
                    <div class="card card-body">
                        <form action="{{ route('unit.update',$unit->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row mb-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Nama Unit</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" placeholder="Masukan Nama Unit"
                                        name="name" value="{{ $unit->name }}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="latitude" class="col-sm-2 col-form-label">Lokasi</label>
                                <div class="col row">
                                    <div class="col-6">
                                        <input type="text" class="form-control" id="latitude" placeholder="latitude"
                                            name="latitude" value="{{ $unit->latitude }}">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control" id="longitude" placeholder="longitude"
                                            name="longitude" value="{{ $unit->longitude }}">
                                    </div>
                                    <div id="map" class="mt-2"></div>
                                </div>
                            </div>
                            <div class="row mb-3 mt-3">
                                <label for="inputEmail" class="col-sm-2 col-form-label">Logo Unit</label>
                                <div class="col-sm-10">
                                    <img id="previewImage" class="mb-2" src="{{ asset('uploads/imgCover/' .
                                        $unit->logo) }}" width=" 50%" alt="">
                                    <input type="file" name="logo"
                                        class="form-control @error('logo') is-invalid @enderror" id="logo">
                                </div>
                            </div>
                            <div class="text-end">
                                <button class="btn btn-primary mt-2" type="submit">Update</button>
                            </div>
                        </form>
                    </div>


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
            center: [{{ $unit->latitude}}, {{ $unit->longitude  }}],
            zoom: 12,
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
        var curLocation = [{{ $unit->latitude}}, {{ $unit->longitude  }}];
        map.attributionControl.setPrefix(false);


        // set marker map agar bisa di geser
        var marker = new L.marker(curLocation, {
            draggable: 'true',
        });
        map.addLayer(marker);


        // ketika marker di geser kita akan mengambil nilai latitude dan longitude
        // kemudian memasukkan nilai tersebut ke dalam properti input text dengan name-nya location
        marker.on('dragend', function(event) {
            var location = marker.getLatLng();
            marker.setLatLng(location, {
                draggable: 'true',
            }).bindPopup(location).update();
            $('#latitude').val(location.lat).keyup()
            $('#longitude').val(location.lng).keyup()
        });


        // untuk fungsi di bawah akan mengambil nilai latitude dan longitudenya
        // dengan cara klik lokasi pada map dan secara otomatis marker juga akan ikut bergeser dan nilai
        // latitude dan longitudenya akan muncul pada input text location
        var latitude = document.querySelector("#latitude");
        var longitude = document.querySelector("#longitude");
        map.on("click", function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;
            if (!marker) {
                marker = L.marker(e.latlng).addTo(map);
            } else {
                marker.setLatLng(e.latlng);
            }
            // loc.value = lat + "," + lng;
            latitude.value = lat;
            longitude.value=lng;
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#logo").change(function() {
            readURL(this);
        });

        $("#latitude,#longitude").change(function() {
        var position = [$("#latitude").val(), $("#longitude").val()];
        marker.setLatLng(position, {
            draggable: 'true'
        }).bindPopup(position).update();
        map.panTo(position);
    });

</script>
@endpush
@endsection
