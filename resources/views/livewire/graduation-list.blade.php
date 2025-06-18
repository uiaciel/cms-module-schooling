<div>

    <div class="d-flex mb-3">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item"><a href="#">Graduation</a></li>
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
                    <h4 class="card-title">Sistem Informasi Kelulusan</h4>
                    <p class="card-text">Tambahkan Data Kelulusan melalui form dibawah ini, setelah itu input data siswa
                        atau import dari file yang sudah ada</p>

                    <livewire:schooling::graduation-create />
                </div>
            </div>
        </div>
        <div class="col-lg-8">

            <div class="card">

                <div class="card-body">
                    <h3 class="mb-3">Data</h3>

                    <ul class="list-group">

                        @foreach ($graduations as $graduation )

                        <li class="list-group-item" aria-current="true">
                            <div class="d-flex justify-content-between align-items-center">

                                <div>
                                    <h3>Tahun Kelulusan {{ $graduation->year }}</h3>
                                    <ul>
                                        <li>Dapat diakses : {{ \Carbon\Carbon::parse($graduation->open_date)->format('d
                                            F Y') }}</li>
                                        <li>Penutupan Akses {{ \Carbon\Carbon::parse($graduation->close_date)->format('d
                                            F Y') }}</li>
                                        <li>Status :<span class="text-danger"> {{ $graduation->status }}</span></li>
                                    </ul>

                                </div>

                                <a href="/admin/graduation/{{ $graduation->year }}" class="btn btn-primary btn-sm"
                                    wire:navigate='true'> <i class="ti ti-database-export"></i>
                                    Data</a>
                            </div>
                        </li>
                        @endforeach

                    </ul>

                </div>
            </div>
        </div>
    </div>

</div>