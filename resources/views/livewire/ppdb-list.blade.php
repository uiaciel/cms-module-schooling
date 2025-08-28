<div>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0 text-secondary">📝 PPDB <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#ppdbModal">
                    Create
                </button> </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Admin</a></li>
                    <li class="breadcrumb-item"><a href="#" class="text-decoration-none">PPDB</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Index</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="ppdbModal" tabindex="-1" aria-labelledby="ppdbModalLabel" aria-hidden="true">
        <div class="modal-dialog        ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ppdbModalLabel">PPDB Create</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <livewire:schooling::ppdb-create />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session()->has('message'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <strong>Info!</strong> {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Validation Error!</strong> Please check the form for errors.
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">

        <div class="col-lg-12">
            <div class="card">

                <div class="card-body">
                    <ul class="list-group">
                        @foreach ($ppdbs as $ppdb )

                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <p><strong>PPDB {{ $ppdb->year }}</strong>
                                    <br />Deskripsi : {{ $ppdb->description }}
                                    <br />Tanggal : {{ $ppdb->start_date }} s/d {{ $ppdb->end_date }}
                                </p>
                            </div>
                            <div>
                                <a href="/admin/ppdb/{{ $ppdb->year }}" class="btn btn-primary btn-sm me-3"
                                    wire:navigate='true'> <i class="ti ti-database-export"></i>
                                    Data Masuk</a>
                                <a href="/ppdb/{{$ppdb->year}}/daftar" target="_blank">Link Registrasi</a>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                </div>
            </div>
        </div>
    </div>
</div>