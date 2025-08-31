<div>
    {{-- Baris 1: Header & Info --}}
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="mb-1">Data PPDB {{ $year }}</h3>
                    <div class="row">
                        <div class="col-md-4">
                            {{ $ppdb->description ?? 'Deskripsi belum tersedia.' }}
                            <div class="mb-2 text-muted small">
                                <span class="me-3"><i class="bi bi-calendar-event"></i>

                                    Buka: {{ \Carbon\Carbon::parse($ppdb->start_date)->format('d M Y') }}
                                    | Tutup: {{ \Carbon\Carbon::parse($ppdb->end_date)->format('d M Y') }}
                                </span>

                            </div>
                        </div>

                        <div class="col-md-8">
                            <form wire:submit.prevent="filter">

                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Cari Kode Registrasi"
                                        wire:model.defer="filter_registration_code">
                                    <input type="text" class="form-control" placeholder="Cari Nama Lengkap"
                                        wire:model.defer="filter_full_name">
                                    <select class="form-select" wire:model.defer="filter_status">
                                        <option value="">Semua Status</option>
                                        @foreach ($statusOptions as $opt)
                                        <option value="{{ $opt }}">{{ ucfirst($opt) }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary " type="submit">Filter</button>
                                    <button class="btn btn-secondary " type="button"
                                        wire:click="resetFilter">Reset</button>

                                </div>

                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Baris 3: Judul Tabel & Bulk Update --}}
    <div class="row mb-2 align-items-center">

        <div class="col-md-8 text-end">

            <form wire:submit.prevent="bulkUpdateStatus">
                <div class="input-group mb-3">
                    <label class="input-group-text" x-text="`${$wire.selectedIds.length} terpilih`"
                        for="inputGroupSelect01"></label>
                    <select class="form-select w-20" wire:model.defer="bulk_status">
                        <option value="">Pilih Status</option>
                        @foreach ($statusOptions as $opt)
                        <option value="{{ $opt }}">{{ ucfirst($opt) }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary" type="submit" wire:click="bulkUpdateStatus"
                        wire:loading.attr="disabled" x-bind:disabled="!$wire.selectedIds.length">
                        Update Terpilih
                    </button>

                </div>

            </form>
        </div>
        <div class="col-md-4">
            <div class="mb-1">
                <span class="badge bg-primary fs-6">Total: {{ $totalRegistrasi }}</span>
                <span class="badge bg-success fs-6">Terverifikasi: {{ $totalVerifikasi }}</span>
                <span class="badge bg-warning text-dark fs-6">Belum Verifikasi: {{ $totalNonVerifikasi }}</span>
            </div>

        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="table-responsive">

        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>

                    <th><input type="checkbox" wire:model="selectAll"></th>
                    <th>Kode Registrasi</th>
                    <th>Tanggal Register</th>
                    <th>Nama Lengkap</th>
                    <th>Tempat, Tanggal Lahir</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($registrations as $reg)
                <tr>
                    <td><input type="checkbox" wire:model="selectedIds" value="{{ $reg->id }}"></td>
                    <td>{{ $reg->registration_code }}</td>
                    <td>{{ $reg->registered_at ? \Carbon\Carbon::parse($reg->registered_at)->format('d-m-Y H:i') :
                        '-' }}</td>
                    <td>{{ $reg->applicant->full_name ?? '-' }}</td>
                    <td>
                        {{ $reg->applicant->place_of_birth ?? '-' }},
                        {{ $reg->applicant->date_of_birth ?
                        \Carbon\Carbon::parse($reg->applicant->date_of_birth)->format('d-m-Y') : '-' }}
                    </td>
                    <td><span class="badge bg-info">{{ ucfirst($reg->status) }}</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary" wire:click="showDetail({{ $reg->id }})">
                            Detail &amp; Verifikasi
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data pendaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    @if ($showModal && $selectedRegistration)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.3)">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pendaftaran: {{ $selectedRegistration->registration_code }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="card mb-4">
                                <div class="card-body text-center">
                                    <h6 class="mb-3 card-title">Foto Pendaftar</h6>
                                    @if ($selectedRegistration->applicant->document &&
                                    $selectedRegistration->applicant->document->photo)
                                    <img src="{{ route('admin.file', $selectedRegistration->applicant->document->photo) }}"
                                        class="img-fluid rounded border mb-2" alt="Pas Foto"
                                        style="max-height: 200px; object-fit: cover;">
                                    <a href="{{ route('admin.file', $selectedRegistration->applicant->document->photo) }}"
                                        target="_blank" class="d-block">Lihat Penuh</a>
                                    @else
                                    <div class="text-muted">Tidak ada foto</div>
                                    <img src="https://via.placeholder.com/150" class="img-fluid rounded border mb-2"
                                        alt="No Photo" style="max-height: 200px; object-fit: cover;">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h6 class="mb-3 card-title">Data Registrasi</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">Kode Registrasi</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0"><strong>{{
                                                            $selectedRegistration->registration_code
                                                            }}</strong></p>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">Tanggal Register</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0">{{ $selectedRegistration->registered_at
                                                        }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">Catatan</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0">{{ $selectedRegistration->notes }}</p>
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">Status</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0"><span class="badge bg-info">{{
                                                            $selectedRegistration->status
                                                            }}</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-3">
                                    <h6 class="mb-3 card-title">Ubah Status Pendaftaran</h6>
                                    <form wire:submit.prevent="updateStatus">
                                        <div class="row align-items-end">
                                            <div class="col-md-8 mb-2 mb-md-0">
                                                <select id="status" class="form-select" wire:model="status">
                                                    @foreach ($statusOptions as $opt)
                                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-success w-100">Simpan
                                                    Status</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h6 class="mb-3 card-title">Data Siswa</h6>
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">Nama Lengkap</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0">{{
                                                        $selectedRegistration->applicant->full_name ??
                                                        '-' }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">Nama Panggilan</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0">{{
                                                        $selectedRegistration->applicant->nickname ??
                                                        '-' }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">Jenis Kelamin</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0">{{
                                                        $selectedRegistration->applicant->gender ??
                                                        '-' }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">Tempat, Tgl Lahir</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0">{{
                                                        $selectedRegistration->applicant->place_of_birth ?? '-' }}, {{
                                                        $selectedRegistration->applicant->date_of_birth ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">Agama</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0">{{
                                                        $selectedRegistration->applicant->religion ??
                                                        '-' }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">Alamat</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0">{{
                                                        $selectedRegistration->applicant->address ??
                                                        '-' }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">No. HP</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0">{{
                                                        $selectedRegistration->applicant->phone ?? '-'
                                                        }}</p>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">Asal Sekolah</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0">{{
                                                        $selectedRegistration->applicant->previous_school ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h6 class="mb-3 card-title">Data Orang Tua</h6>
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">Nama Ayah</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0">{{
                                                        $selectedRegistration->applicant->parent->father_name ?? '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">Pekerjaan Ayah</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0">{{
                                                        $selectedRegistration->applicant->parent->father_job ?? '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">Nama Ibu</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0">{{
                                                        $selectedRegistration->applicant->parent->mother_name ?? '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">Pekerjaan Ibu</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0">{{
                                                        $selectedRegistration->applicant->parent->mother_job ?? '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">Alamat</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0">{{
                                                        $selectedRegistration->applicant->parent->parent_address ?? '-'
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <div class="row mb-2">
                                                <div class="col-sm-5">
                                                    <p class="mb-0">No. Telepon</p>
                                                </div>
                                                <div class="col-sm-7">
                                                    <p class="text-muted mb-0">{{
                                                        $selectedRegistration->applicant->parent->parent_phone ?? '-' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h6 class="mb-3 card-title">Kebutuhan Khusus</h6>
                                            <p class="text-muted mb-0">{{
                                                $selectedRegistration->applicant->notes ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <h6 class="mb-3 card-title">Dokumen Terupload</h6>
                                            <div class="row g-3"> {{-- g-3 untuk gap antar kolom --}}
                                                @if ($selectedRegistration->applicant->document)
                                                @if ($selectedRegistration->applicant->document->birth_certificate)
                                                <div class="col-6 col-md-3"> {{-- Menggunakan col-6 untuk 2 kolom di
                                                    mobile,
                                                    col-md-3 untuk 4 kolom
                                                    di desktop --}}
                                                    <div class="document-item text-center">
                                                        <label class="form-label text-muted d-block mb-2">Akta
                                                            Kelahiran</label>
                                                        <a href="{{ route('admin.file', $selectedRegistration->applicant->document->birth_certificate) }}"
                                                            target="_blank">
                                                            <img src="{{ route('admin.file', $selectedRegistration->applicant->document->birth_certificate) }}"
                                                                class="img-fluid rounded border" alt="Akta Kelahiran"
                                                                style="height: 150px; width: 100%; object-fit: cover;">
                                                        </a>
                                                        <small class="d-block mt-2"><a
                                                                href="{{ route('admin.file', $selectedRegistration->applicant->document->birth_certificate) }}"
                                                                target="_blank">Lihat</a></small>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($selectedRegistration->applicant->document->family_card)
                                                <div class="col-6 col-md-3">
                                                    <div class="document-item text-center">
                                                        <label class="form-label text-muted d-block mb-2">Kartu
                                                            Keluarga</label>
                                                        <a href="{{ route('admin.file', $selectedRegistration->applicant->document->family_card) }}"
                                                            target="_blank">
                                                            <img src="{{ route('admin.file', $selectedRegistration->applicant->document->family_card) }}"
                                                                class="img-fluid rounded border" alt="Kartu Keluarga"
                                                                style="height: 150px; width: 100%; object-fit: cover;">
                                                        </a>
                                                        <small class="d-block mt-2"><a
                                                                href="{{ route('admin.file', $selectedRegistration->applicant->document->family_card) }}"
                                                                target="_blank">Lihat</a></small>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($selectedRegistration->applicant->document->photo)
                                                <div class="col-6 col-md-3">
                                                    <div class="document-item text-center">
                                                        <label class="form-label text-muted d-block mb-2">Pas
                                                            Foto</label>
                                                        <a href="{{ route('admin.file', $selectedRegistration->applicant->document->photo) }}"
                                                            target="_blank">
                                                            <img src="{{ route('admin.file', $selectedRegistration->applicant->document->photo) }}"
                                                                class="img-fluid rounded border" alt="Pas Foto"
                                                                style="height: 150px; width: 100%; object-fit: cover;">
                                                        </a>
                                                        <small class="d-block mt-2"><a
                                                                href="{{ route('admin.file', $selectedRegistration->applicant->document->photo) }}"
                                                                target="_blank">Lihat</a></small>
                                                    </div>
                                                </div>
                                                @endif
                                                @if ($selectedRegistration->applicant->document->certificate_pa)
                                                <div class="col-6 col-md-3">
                                                    <div class="document-item text-center">
                                                        <label class="form-label text-muted d-block mb-2">Sertifikat
                                                            Pendidikan
                                                            Agama</label>
                                                        <a href="{{ route('admin.file', $selectedRegistration->applicant->document->certificate_pa) }}"
                                                            target="_blank">
                                                            <img src="{{ route('admin.file', $selectedRegistration->applicant->document->certificate_pa) }}"
                                                                class="img-fluid rounded border"
                                                                alt="Sertifikat Pendidikan Agama"
                                                                style="height: 150px; width: 100%; object-fit: cover;">
                                                        </a>
                                                        <small class="d-block mt-2"><a
                                                                href="{{ route('admin.file', $selectedRegistration->applicant->document->certificate_pa) }}"
                                                                target="_blank">Lihat</a></small>
                                                    </div>
                                                </div>
                                                @endif
                                                @else
                                                <div class="col-12">
                                                    <p class="text-muted text-center">Tidak ada dokumen terupload.</p>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</div>
