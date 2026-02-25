@extends('layouts.main')

@section('title', 'Laksamana Export')

@section('content')
    <div class="container">
        <h2 class="my-4 text-center">Export Laksamana Business Data</h2>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p class="lead">Download an XLSX containing only businesses with coordinates (latitude & longitude).
                            The file includes these fields: id, idsbr, nama_usaha, kecamatan, kelurahan, latitude, longitude.
                        </p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <span class="badge bg-success">Authenticated BPS Access</span>
                            </div>
                            <a href="{{ route('laksamana.export.xlsx') }}" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Download XLSX
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection