@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Users') }}</div>

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
                            <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Nama</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" placeholder="Masukan Nama"
                                            name="name">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" placeholder="Masukan Email"
                                            name="email">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="password"
                                            placeholder="Masukan password" name="password">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputEmail" class="col-sm-2 col-form-label">Unit</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" aria-label="Unit" name="unit" id="unit">
                                            <option selected hidden>Open this select menu</option>
                                            @foreach($units as $unit)

                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
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
                                    <th scope="col">Email</th>
                                    <th scope="col">Unit</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <th>{{ @$user->userHasUnit->unit->name }}</th>
                                    <th>{{ $user->getRoleNames()->first() }}</th>
                                    <td>
                                        <a class="btn btn-info" href="xx">Edit</a>

                                        <button class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#exampleModal" data-route="xx">Delete</button>
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
@endsection
