<div>
    <div class="d-flex mb-3">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item"><a href="#">PPDB</a></li>
                <li class="breadcrumb-item active"><a href="#">Index</a></li>
                {{-- <li class="breadcrumb-item active" aria-current="page">Data</li> --}}
            </ol>
        </nav>
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
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    {{-- <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                        card's content.</p>
                    <a href="#" class="btn btn-primary">Go somewhere</a>
                    --}}

                    <livewire:schooling::ppdb-create />
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">

                <div class="card-body">
                    <h3 class="mb-3">List PPDB</h3>

                    <ul class="list-group">
                        @foreach ($ppdbs as $ppdb )

                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <p><strong>PPDB {{ $ppdb->year }} - {{ $ppdb->description }} </strong>

                                    <br />Tanggal : {{ $ppdb->start_date }} s/d {{ $ppdb->end_date }}
                                </p>
                            </div>
                            <div>
                                <a href="/admin/ppdb/{{ $ppdb->year }}" class="btn btn-primary btn-sm"
                                    wire:navigate='true'> <i class="ti ti-database-export"></i>
                                    Data</a>
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