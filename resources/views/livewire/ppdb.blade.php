<div>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/locale/id.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/relativeTime.js"></script>
    <script>
        dayjs.extend(window.dayjs_plugin_relativeTime);
        dayjs.locale('id');
    </script>

    <section class="bg-light">
        <div class="container mt-4 py-5 px-5">

            <div class="row mt-2">
                <div class="col-md-12 text-center">
                    <h2 class="fw-bold">PPDB {{ $ppdb->year }}</h2>
                    <p>{{$ppdb->description}}</p>
                </div>
                <div class="col-md-6">
                    <div class="card position-relative"> {{-- Added position-relative here --}}
                        <img src="/storage/{{ $ppdb->brochure_img }}" class="card-img-top" alt="...">
                        <div class="position-absolute bottom-0 end-0 p-3"> {{-- Added position-absolute for the button
                            --}}
                            <a href="/storage/{{ $ppdb->brochure_pdf }}" class="btn btn-primary">Download Brosur</a>
                        </div>

                    </div>
                </div>
                <div class="col-md-6" x-data="{
                    search: '',
                    page: 1,
                    perPage: 10,
                    registrations: @js($ppdbs),
                    get filtered() {
                        if (!this.search) return this.registrations;
                        return this.registrations.filter(r =>
                            (r.registration_code ?? '').toLowerCase().includes(this.search.toLowerCase()) ||
                            (r.applicant && r.applicant.full_name ? r.applicant.full_name.toLowerCase().includes(this.search.toLowerCase()) : false)
                        );
                    },
                    get paginated() {
                        const start = (this.page - 1) * this.perPage;
                        return this.filtered.slice(start, start + this.perPage);
                    },
                    get totalPages() {
                        return Math.ceil(this.filtered.length / this.perPage) || 1;
                    }
                }">
                    <div class="alert alert-primary" role="alert">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="mb-0">Pendaftaran Online mulai tanggal :</p>
                                <p class="fw-bold">{{ \Carbon\Carbon::parse($ppdb->start_date)->format('d M Y') }} s/d
                                    {{ \Carbon\Carbon::parse($ppdb->end_date)->format('d M Y') }}</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <a href="#formulir" class="btn btn-primary">Daftar Online</a>
                            </div>

                        </div>

                    </div>

                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Cari Nama/Kode Registrasi..."
                            x-model="search">
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hovered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Kode Registrasi</th>
                                            <th scope="col">Nama Lengkap</th>
                                            <th scope="col">Tanggal Registrasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="registration in paginated" :key="registration.id">
                                            <tr>
                                                <td x-text="registration.registration_code ?? '-'"></td>
                                                <td
                                                    x-text="registration.applicant && registration.applicant.full_name ? registration.applicant.full_name : '-'">
                                                </td>
                                                <td
                                                    x-text="registration.created_at ? window.dayjs(registration.created_at).fromNow() : '-'">
                                                </td>
                                            </tr>
                                        </template>
                                        <template x-if="paginated.length === 0">
                                            <tr>
                                                <td colspan="3" class="text-center">Data tidak ditemukan.</td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination Controls -->
                            <nav>
                                <ul class="pagination justify-content-center">
                                    <li class="page-item" :class="{ 'disabled': page === 1 }">
                                        <button class="page-link" @click="if(page > 1) page--">Sebelumnya</button>
                                    </li>
                                    <template x-for="p in totalPages" :key="p">
                                        <li class="page-item" :class="{ 'active': page === p }">
                                            <button class="page-link" @click="page = p" x-text="p"></button>
                                        </li>
                                    </template>
                                    <li class="page-item" :class="{ 'disabled': page === totalPages }">
                                        <button class="page-link"
                                            @click="if(page < totalPages) page++">Berikutnya</button>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>

                </div>

                {{-- @push('scripts')
                @endpush --}}

            </div>

        </div>
    </section>
    <section class="bg-light-subtle">
        <div class="container mt-4 py-5" id="formulir">
            @push('scripts')
            <script>
                // Prevent accidental reload/refresh/close during registration steps 1-4
                window.addEventListener('beforeunload', function (e) {
                    // Cek jika masih di step proses (belum selesai registrasi)
                    @this.step = @json($step);
                    if (@this.step > 0 && @this.step < 5) {
                        e.preventDefault();
                        e.returnValue = 'Data pendaftaran Anda belum selesai. Yakin ingin meninggalkan halaman ini?';
                        return 'Data pendaftaran Anda belum selesai. Yakin ingin meninggalkan halaman ini?';
                    }
                });

                // Hilangkan warning jika sudah selesai (step 5)
                window.livewire.on('registrationFinished', () => {
                    window.onbeforeunload = null;
                });

                window.livewire.on('downloadPdf', (url) => {
                    window.open(url, '_blank');
                });
            </script>
            @endpush

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-lg p-4">
                        <div class="card-header bg-success text-white text-center rounded-top">
                            <h2 class="mb-0">Pendaftaran PPDB {{ $ppdb->year }}</h2>
                        </div>
                        <div class="card-body">

                            {{-- Stepper --}}
                            <div class="d-flex justify-content-around mb-4">
                                <div class="text-center">
                                    <span
                                        class="badge rounded-pill p-2 {{ $step == 1 ? 'bg-success' : 'bg-secondary' }}">1</span>
                                    <div class="mt-2 d-none d-md-block">Data Siswa</div>
                                </div>
                                <div class="text-center">
                                    <span
                                        class="badge rounded-pill p-2 {{ $step == 2 ? 'bg-success' : 'bg-secondary' }}">2</span>
                                    <div class="mt-2 d-none d-md-block">Data Orang Tua</div>
                                </div>
                                <div class="text-center">
                                    <span
                                        class="badge rounded-pill p-2 {{ $step == 3 ? 'bg-success' : 'bg-secondary' }}">3</span>
                                    <div class="mt-2 d-none d-md-block">Upload Dokumen</div>
                                </div>
                                <div class="text-center">
                                    <span
                                        class="badge rounded-pill p-2 {{ $step == 4 ? 'bg-success' : 'bg-secondary' }}">4</span>
                                    <div class="mt-2 d-none d-md-block">Registrasi</div>
                                </div>
                            </div>

                            <div class="progress mb-4" style="height: 10px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                    style="width: {{ ($step / 4) * 100 }}%" aria-valuenow="{{ ($step / 4) * 100 }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>

                            @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                            @endif

                            {{-- STEP 1: DATA SISWA --}}
                            @if ($step == 1)
                            <form wire:submit.prevent="nextStep"
                                x-data="{ hasSpecialNeeds: @entangle('applicant.has_special_needs').defer }">
                                <h4 class="mb-4">Data Siswa</h4>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="full_name" class="form-label">Nama Lengkap</label>
                                        <input type="text" id="full_name" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            wire:model.defer="applicant.full_name" placeholder="Nama Lengkap" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nickname" class="form-label">Nama Panggilan</label>
                                        <input type="text" id="nickname" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            wire:model.defer="applicant.nickname" placeholder="Nama Panggilan">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="gender" class="form-label">Jenis Kelamin</label>
                                        <select id="gender" class="form-select" wire:model.defer="applicant.gender"
                                            required>
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="religion" class="form-label">Agama</label>
                                        <select id="religion" class="form-select" wire:model.defer="applicant.religion"
                                            required>
                                            <option value="">Pilih Agama</option>
                                            <option value="Islam">Islam</option>
                                            <option value="Kristen">Kristen</option>
                                            <option value="Katolik">Katolik</option>
                                            <option value="Hindu">Hindu</option>
                                            <option value="Buddha">Buddha</option>
                                            <option value="Konghucu">Konghucu</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="place_of_birth" class="form-label">Tempat Lahir</label>
                                        <input type="text" id="place_of_birth" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            wire:model.defer="applicant.place_of_birth" placeholder="Tempat Lahir"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="date_of_birth" class="form-label">Tanggal Lahir</label>
                                        <input type="date" id="date_of_birth" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            wire:model.defer="applicant.date_of_birth" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="address" class="form-label">Alamat Lengkap</label>
                                        <textarea id="address" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            wire:model.defer="applicant.address" placeholder="Alamat Lengkap"
                                            required></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">No. Telepon/HP</label>
                                        <input type="number" id="phone" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            wire:model.defer="applicant.phone" placeholder="No. Telepon/HP" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="previous_school" class="form-label">Asal Sekolah</label>
                                        <input type="text" id="previous_school" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            wire:model.defer="applicant.previous_school" placeholder="Asal Sekolah">
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Apakah ananda memiliki kebutuhan khusus</label><br>
                                            <div class="d-flex">
                                                <div class="form-check me-4">
                                                    <input class="form-check-input" type="radio"
                                                        name="has_special_needs" id="hasSpecialNeedsYes" value="1"
                                                        x-model="hasSpecialNeeds">
                                                    <label class="form-check-label" for="hasSpecialNeedsYes">
                                                        Ya
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="has_special_needs" id="hasSpecialNeedsNo" value="0"
                                                        x-model="hasSpecialNeeds">
                                                    <label class="form-check-label" for="hasSpecialNeedsNo">
                                                        Tidak
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Tampilkan input notes hanya jika 'Ya' dipilih --}}
                                    <div class="col-md-12" x-show="hasSpecialNeeds == 1">
                                        <label for="notes" class="form-label fw-bold">Mohon jelaskan kebutuhan
                                            khusus
                                            yang
                                            dimiliki ananda (jenis, kondisi, atau penanganan yang pernah dilakukan)
                                            ?</label>
                                        <textarea id="notes" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            wire:model.defer="applicant.notes"
                                            :required="hasSpecialNeeds == 1"></textarea>
                                    </div>

                                </div>
                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-success btn-lg">Lanjut</button>
                                </div>
                            </form>

                            {{-- STEP 2: DATA ORANG TUA --}}
                            @elseif ($step == 2)
                            <form wire:submit.prevent="nextStep">
                                <h4 class="mb-4">Data Orang Tua</h4>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="father_name" class="form-label">Nama Ayah</label>
                                        <input type="text" id="father_name" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            wire:model.defer="parent.father_name" placeholder="Nama Ayah" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="father_job" class="form-label">Pekerjaan Ayah</label>
                                        <input type="text" id="father_job" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            wire:model.defer="parent.father_job" placeholder="Pekerjaan Ayah" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="mother_name" class="form-label">Nama Ibu</label>
                                        <input type="text" id="mother_name" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            wire:model.defer="parent.mother_name" placeholder="Nama Ibu" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="mother_job" class="form-label">Pekerjaan Ibu</label>
                                        <input type="text" id="mother_job" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            wire:model.defer="parent.mother_job" placeholder="Pekerjaan Ibu" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="parent_address" class="form-label">Alamat Orang Tua</label>
                                        <textarea id="parent_address" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            wire:model.defer="parent.parent_address" placeholder="Alamat Orang Tua"
                                            required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="parent_phone" class="form-label">No. Telepon Orang Tua</label>
                                        <input type="number" id="parent_phone" class="form-control"
                                            oninput="this.value = this.value.toUpperCase()"
                                            wire:model.defer="parent.parent_phone" placeholder="No. Telepon Orang Tua"
                                            required>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" wire:click="prevStep" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-2"></i> Kembali
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        Lanjut <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </form>

                            {{-- STEP 3: UPLOAD DOKUMEN --}}
                            @elseif ($step == 3)

                            <form wire:submit.prevent="nextStep" enctype="multipart/form-data">
                                <h4 class="mb-4">Upload Dokumen</h4>
                                <div class="mb-3">
                                    <label for="birth_certificate" class="form-label">Akta Kelahiran</label>
                                    <input class="form-control" type="file" id="birth_certificate"
                                        wire:model.defer="birth_certificate" accept="image/*,application/pdf" required>
                                    <div class="form-text fst-italic text-muted" id="basic-addon4">Format Foto JPG, PNG,
                                        GIF, Maksimal 5mb.</div>
                                    {{-- Preview for image --}}
                                    @if ($birth_certificate)
                                    @if (Str::startsWith($birth_certificate->getMimeType(), 'image/'))
                                    <div class="mt-2">
                                        <img src="{{ $birth_certificate->temporaryUrl() }}" alt="Preview Akta Kelahiran"
                                            class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                    @endif
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="family_card" class="form-label">Kartu Keluarga</label>
                                    <input class="form-control" type="file" id="family_card"
                                        wire:model.defer="family_card" accept="image/*,application/pdf">
                                    <div class="form-text fst-italic text-muted" id="basic-addon4">Format Foto JPG, PNG,
                                        GIF, Maksimal 5mb.</div>
                                    @if ($family_card)
                                    @if (Str::startsWith($family_card->getMimeType(), 'image/'))
                                    <div class="mt-2">
                                        <img src="{{ $family_card->temporaryUrl() }}" alt="Preview Kartu Keluarga"
                                            class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                    @endif
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="photo" class="form-label">Pas Foto (3x4)</label>
                                    <input class="form-control" type="file" id="photo" wire:model.defer="photo"
                                        accept="image/*">
                                    <div class="form-text fst-italic text-muted" id="basic-addon4">Format Foto JPG, PNG,
                                        GIF, Maksimal 5mb.</div>
                                    @if ($photo)
                                    <div class="mt-2">
                                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview Pas Foto"
                                            class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="certificate_pa" class="form-label">Ijazah/Sertifikat (Opsional)</label>
                                    <input class="form-control" type="file" id="certificate_pa"
                                        wire:model.defer="certificate_pa" accept="image/*,application/pdf">
                                    <div class="form-text fst-italic text-muted" id="basic-addon4">Format Foto JPG, PNG,
                                        GIF, Maksimal 5mb.</div>
                                    @if ($certificate_pa)
                                    @if (Str::startsWith($certificate_pa->getMimeType(), 'image/'))
                                    <div class="mt-2">
                                        <img src="{{ $certificate_pa->temporaryUrl() }}" alt="Preview Sertifikat"
                                            class="img-thumbnail" style="max-height: 150px;">
                                    </div>
                                    @endif
                                    @endif
                                </div>
                                <div wire:loading
                                    wire:target="birth_certificate, family_card, photo, certificate_pa, nextStep"
                                    class="mb-3">
                                    <div class="alert alert-info d-flex align-items-center" role="alert">
                                        <span class="spinner-border spinner-border-sm me-2" role="status"
                                            aria-hidden="true"></span>
                                        Mengunggah file, mohon tunggu...
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" wire:click="prevStep" class="btn btn-secondary">
                                        <i class="bi bi-arrow-left me-2"></i> Kembali
                                    </button>

                                    <button type="submit" class="btn btn-success" wire:click="nextStep"
                                        wire:loading.attr="disabled">
                                        <i class="bi bi-check-circle me-2"></i> Kirim Data
                                    </button>
                                </div>
                            </form>

                            {{-- STEP 4: REGISTRASI & BUKTI PENDAFTARAN --}}
                            @elseif ($step == 4)
                            <div class="alert alert-success text-center">
                                <h4 class="mb-3">Pendaftaran Berhasil!</h4>
                                <p class="mb-1">Kode Registrasi Anda:</p>
                                <h3 class="mb-3"><strong>{{ $registration_code }}</strong></h3>
                                <p>Status: <span class="badge bg-info">Berhasil Registrasi</span></p>
                                <p>Catatan: <em>Menunggu Verifikasi</em></p>
                                <p class="mt-4">
                                    <a href="{{ $pdf_link }}" target="_blank" class="btn btn-success">
                                        <i class="bi bi-file-earmark-pdf me-2"></i> Download Bukti Pendaftaran (PDF)
                                    </a>
                                </p>
                                <hr>
                                <p class="text-muted small mb-0">Simpan kode registrasi dan bukti pendaftaran Anda.
                                    Silakan
                                    menunggu proses verifikasi dari panitia PPDB.</p>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
