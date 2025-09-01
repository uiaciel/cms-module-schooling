<div>
    <div class="card shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Daftar PPDB</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-sm btn-primary" wire:click="createPpdb">
                    Buka PPDB Baru
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session()->has('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session()->has('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif



            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Tahun</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ppdbs as $ppdb)
                        <tr>
                            <td><a href="/ppdb/online/{{ $ppdb->year }}">{{ $ppdb->year }}</a>
                                <p class="text-muted">{{$ppdb->description}}</p>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($ppdb->start_date)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($ppdb->end_date)->format('d M Y') }}</td>
                            <td><span class="badge bg-{{ $ppdb->status == 'active' ? 'success' : 'secondary' }}">{{
                                    ucfirst($ppdb->status) }}</span></td>
                            <td>
                                <a href="/admin/ppdb/{{ $ppdb->year }}" class="btn btn-sm btn-success">Detail</a>
                                <button class="btn btn-sm btn-primary"
                                    wire:click="editPpdb({{ $ppdb->id }})">Edit</button>
                                <a href="#" wire:click="exportExcel({{ $ppdb->id }})" class="btn btn-sm btn-success">
                                    <i class="fas fa-file-excel"></i> Export
                                </a>
                                @if(Auth::id() == 1)
                                <button class="btn btn-sm btn-danger" wire:click="deletePpdb({{ $ppdb->id }})"
                                    wire:confirm="Anda yakin ingin menghapus periode ini beserta semua data pendaftarnya?">Hapus</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data periode PPDB.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    @if ($showEditModal)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.3)">
        <div class="modal-dialog @if($editingPpdbId) modal-lg @else modal-sm @endif modal-dialog-centered">
            <div class="modal-content">
                <form wire:submit.prevent="savePpdb" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editingPpdbId ? 'Edit' : 'Tambah' }} Periode PPDB</h5>
                        <button type="button" class="btn-close" wire:click="$set('showEditModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @if($editingPpdbId && $ppdb->brochure_img)
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <img src="/storage/{{ $ppdb->brochure_img }}" class="img-fluid" />
                                </div>
                            </div>
                            <div class="col-md-8">
                                @else
                                <div class="col-md-12">
                                    @endif
                                    <div class="mb-3">
                                        <label for="year" class="form-label">Tahun</label>
                                        <input type="number" id="year"
                                            class="form-control @error('year') is-invalid @enderror"
                                            wire:model.defer="year" placeholder="Contoh: 2024">
                                        @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                                        <input type="date" id="start_date"
                                            class="form-control @error('start_date') is-invalid @enderror"
                                            wire:model.defer="start_date">
                                        @error('start_date') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="end_date" class="form-label">Tanggal Selesai</label>
                                        <input type="date" id="end_date"
                                            class="form-control @error('end_date') is-invalid @enderror"
                                            wire:model.defer="end_date">
                                        @error('end_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <textarea id="description"
                                            class="form-control @error('description') is-invalid @enderror"
                                            wire:model.defer="description" rows="3"></textarea>
                                        @error('description') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="brochure_img" class="form-label">Brochure Cover</label>
                                        <input type="file" id="brochure_img" class="form-control"
                                            wire:model.defer="brochure_img">
                                        <div class="form-text" id="brochure_pdf">File Type PNG, JPG, JPEG, Maksimal 2Mb
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="brochure_pdf" class="form-label">Brochure PDF</label>
                                        <input type="file" id="brochure_pdf" class="form-control"
                                            wire:model="brochure_pdf">
                                        <div class="form-text" id="brochure_pdf">File Type PDF, Maksimal 2Mb</div>
                                        @if($editingPpdbId && $ppdb->brochure_pdf)
                                        <a href="/storage/{{ $ppdb->brochure_pdf }}"><i class="fas fa-file-pdf"></i>" {{
                                            $ppdb->brochure_pdf }}</a>
                                        @endif
                                    </div>

                                    <div class=" mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select id="status" class="form-select @error('status') is-invalid @enderror"
                                            wire:model.defer="status">
                                            <option value="">Pilih Status</option>
                                            <option value="active">Aktif</option>
                                            <option value="inactive">Tidak Aktif</option>
                                        </select>
                                        @error('status') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="$set('showEditModal', false)">Batal</button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled"
                                wire:target="savePpdb">Simpan</button>
                        </div>
                </form>
            </div>
        </div>
    </div>

</div>
<div class="modal-backdrop fade show"></div>
@endif
</div>
