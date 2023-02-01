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
                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="text-end">
                        <a class="btn btn-primary p-1 mx-auto" data-bs-toggle="collapse" href="#collapseExample"
                            role="button" aria-expanded="false" aria-controls="collapseExample" type="button">
                            <i class="bi bi-plus" id="icon-plus" style="height: 20px"></i>
                            Tambah Data
                        </a>
                    </div>
                    <p>
                    </p>
                    <div class="collapse" id="collapseExample">
                        <div class="card card-body">
                            <form action="{{ route('unit.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Nama Unit</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name"
                                            placeholder="Masukan Nama Unit" name="name">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="latitude" class="col-sm-2 col-form-label">Lokasi</label>
                                    <div class="col row">
                                        <div class="col-6">
                                            <input type="text" class="form-control" id="latitude" placeholder="latitude"
                                                name="latitude">
                                        </div>
                                        <div class="col-6">
                                            <input type="text" class="form-control" id="longitude"
                                                placeholder="longitude" name="longitude">
                                        </div>
                                    </div>
                                </div>
                                <div id="map"></div>
                                <div class="row mb-3 mt-3">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Logo Unit</label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control" id="logo"
                                            placeholder="Masukan Logo Unit" name="logo">
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button class="btn btn-primary mt-2" type="submit">Tambah</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <section>
                        <hr>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Lokasi</th>
                                    <th scope="col">Logo</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($units as $unit)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $unit->name }}</td>
                                    <td>{{ $unit->latitude }}-{{ $unit->longitude }}</td>
                                    <th><img src="{{  asset('uploads/imgCover/' . $unit->logo)  }}"
                                            class="img-thumbnail" alt="{{ $unit->name }}" style="width:150px"></th>
                                    <td>
                                        <a class="btn btn-info" href="{{ route('unit.edit',$unit->id) }}">Edit</a>

                                        <button class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal"
                                            data-route="{{ route('unit.destroy',$unit->id) }}">Delete</button>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </section>

                </div>


            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" id="delete-form">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    Delete Data
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
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
    var myCollapsible = document.getElementById('collapseExample')
    myCollapsible.addEventListener('show.bs.collapse', function () {
        setTimeout(function () { map.invalidateSize() }, 800);
        var iconPlus = document.getElementById('icon-plus')
        // console.log(iconPlus);
        if (iconPlus.classList.contains('bi-plus')) {
            iconPlus.classList.remove('bi-plus')
            iconPlus.classList.add('bi-dash')
        }
    })

    myCollapsible.addEventListener('hide.bs.collapse', function () {
        var iconPlus = document.getElementById('icon-plus')
        // console.log(iconPlus);
        if(iconPlus.classList.contains('bi-dash')){
            iconPlus.classList.remove('bi-dash')
            iconPlus.classList.add('bi-plus')
        }
    })



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

        $("#latitude,#longitude").change(function() {
        var position = [$("#latitude").val(), $("#longitude").val()];
        marker.setLatLng(position, {
            draggable: 'true'
        }).bindPopup(position).update();
        map.panTo(position);
    });


    jQuery(document).ready(function($) {
            $("#exampleModal").on("show.bs.modal", (event) => {
                // console.log('masuk');
                var button = $(event.relatedTarget);
                var route = button.data('route');
                var deleteForm = document.getElementById('delete-form');

                deleteForm.action = route;

            });
        });
</script>
@endpush
@endsection
