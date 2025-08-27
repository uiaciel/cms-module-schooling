<div class="container py-5">
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
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    {{-- STEP 1: DATA SISWA --}}
                    @if ($step == 1)
                    <form wire:submit.prevent="nextStep">
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
                                <select id="gender" class="form-select" wire:model.defer="applicant.gender" required>
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
                                    wire:model.defer="applicant.place_of_birth" placeholder="Tempat Lahir" required>
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
                                    oninput="this.value = this.value.toUpperCase()" wire:model.defer="applicant.address"
                                    placeholder="Alamat Lengkap" required></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">No. Telepon/HP</label>
                                <input type="text" id="phone" class="form-control"
                                    oninput="this.value = this.value.toUpperCase()" wire:model.defer="applicant.phone"
                                    placeholder="No. Telepon/HP" required>
                            </div>
                            <div class="col-md-6">
                                <label for="previous_school" class="form-label">Asal Sekolah</label>
                                <input type="text" id="previous_school" class="form-control"
                                    oninput="this.value = this.value.toUpperCase()"
                                    wire:model.defer="applicant.previous_school" placeholder="Asal Sekolah">
                            </div>
                            <div class="col-12">
                                <label for="notes" class="form-label fw-bold">Apakah ananda memiliki kebutuhan khusus
                                    ?</label>
                                <textarea id="notes" class="form-control"
                                    oninput="this.value = this.value.toUpperCase()" wire:model.defer="applicant.notes"
                                    placeholder="Apakah Ananda memiliki kebutuhan khusus?" required></textarea>
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
                                    oninput="this.value = this.value.toUpperCase()" wire:model.defer="parent.father_job"
                                    placeholder="Pekerjaan Ayah" required>
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
                                    oninput="this.value = this.value.toUpperCase()" wire:model.defer="parent.mother_job"
                                    placeholder="Pekerjaan Ibu" required>
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
                                <input type="text" id="parent_phone" class="form-control"
                                    oninput="this.value = this.value.toUpperCase()"
                                    wire:model.defer="parent.parent_phone" placeholder="No. Telepon Orang Tua" required>
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

                        {{-- Loading indicator --}}
                        <div wire:loading wire:target="birth_certificate, family_card, photo, certificate_pa, nextStep"
                            class="mb-3">
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <span class="spinner-border spinner-border-sm me-2" role="status"
                                    aria-hidden="true"></span>
                                Mengunggah file, mohon tunggu...
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="birth_certificate" class="form-label">Akta Kelahiran</label>
                            <input class="form-control" type="file" id="birth_certificate"
                                wire:model.defer="birth_certificate" accept="image/*,application/pdf" required>
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
                            <input class="form-control" type="file" id="family_card" wire:model.defer="family_card"
                                accept="image/*,application/pdf">
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
                            @if ($photo)
                            <div class="mt-2">
                                <img src="{{ $photo->temporaryUrl() }}" alt="Preview Pas Foto" class="img-thumbnail"
                                    style="max-height: 150px;">
                            </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="certificate_pa" class="form-label">Sertifikat (Opsional)</label>
                            <input class="form-control" type="file" id="certificate_pa"
                                wire:model.defer="certificate_pa" accept="image/*,application/pdf">
                            @if ($certificate_pa)
                            @if (Str::startsWith($certificate_pa->getMimeType(), 'image/'))
                            <div class="mt-2">
                                <img src="{{ $certificate_pa->temporaryUrl() }}" alt="Preview Sertifikat"
                                    class="img-thumbnail" style="max-height: 150px;">
                            </div>
                            @endif
                            @endif
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" wire:click="prevStep" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Kembali
                            </button>
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                                <i class="bi bi-check-circle me-2"></i> Selesai &amp; Registrasi
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
                        <p class="text-muted small mb-0">Simpan kode registrasi dan bukti pendaftaran Anda. Silakan
                            menunggu proses verifikasi dari panitia PPDB.</p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>