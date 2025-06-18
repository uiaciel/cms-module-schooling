<div x-data="{ showImport: false }">
    <div class="d-flex mb-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
                <li class="breadcrumb-item"><a href="/admin/graduation">Graduation</a></li>
                <li class="breadcrumb-item active"><a href="/admin/graduation/{{ $year }}">{{ $year }}</a></li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3>Graduation {{ $year }}</h3>
                    <p>Bisa Diakses Mulai : {{ \Carbon\Carbon::parse($graduation->open_date)->format('d F Y') }} s/d {{
                        \Carbon\Carbon::parse($graduation->close_date)->format('d F Y') }}</p>
                </div>

                <div>
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#importCollapse" aria-expanded="false" aria-controls="importCollapse">
                        Import File
                    </button>

                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <!-- Collapse Content -->
            <div class="collapse mb-3" id="importCollapse">
                <div class="card card-body">
                    <form wire:submit.prevent="import">
                        <div class="mb-3">
                            <label for="importFile" class="form-label">Upload File</label>
                            <input type="file" id="importFile" class="form-control" wire:model="file"
                                accept=".xls,.xlsx,.csv">
                            @error('file') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal -->

        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if (session()->has('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="mb-3">
                        <h5>Informasi Data</h5>
                        <ul>
                            <li>Total Data : {{ $students->count() }}</li>
                            <li>Total yang telah mengakses : {{ $students->whereNotNull('accessed_at')->count() }}</li>
                            <li>Total yang belum mengakses : {{ $students->whereNull('accessed_at')->count() }}</li>
                        </ul>
                    </div>

                    <table class="table table-sm table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>SK</th>
                                <th>Student</th>
                                <th>NISN</th>
                                <th>Password</th>
                                <th>Graduation</th>
                                <th>Last Access</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                            <tr x-data="{ editing: @js($editRow === $student->id) }">
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>
                                    <template x-if="editing">
                                        <input type="text" class="form-control" wire:model.defer="editData.sk">
                                    </template>
                                    <span x-show="!editing">{{ $student->sk }}</span>
                                </td>
                                {{-- Nama Siswa --}}
                                <td>
                                    <template x-if="editing">
                                        <input type="text" class="form-control" wire:model.defer="editData.name">
                                    </template>
                                    <span x-show="!editing">{{ $student->name }}</span>

                                </td>
                                {{-- NISN --}}
                                <td>
                                    <template x-if="editing">
                                        <input type="text" class="form-control" wire:model.defer="editData.nisn">
                                    </template>
                                    <span x-show="!editing">{{ $student->nisn }}</span>
                                </td>
                                <td>
                                    <template x-if="editing">
                                        <input type="date" class="form-control" wire:model.defer="editData.birth_date"
                                            placeholder="dd-mm-yyyy">
                                    </template>
                                    <span x-show="!editing">
                                        {{ \Carbon\Carbon::parse($student->birth_date)->format('d-m-Y') }}
                                    </span>

                                </td>
                                {{-- Kelulusan --}}
                                <td class="text-center">
                                    <template x-if="editing">
                                        <select class="form-select" wire:model.defer="editData.graduation_status">
                                            <option value="LULUS">LULUS</option>
                                            <option value="TIDAK LULUS">TIDAK LULUS</option>
                                        </select>
                                    </template>
                                    <span x-show="!editing">{{ $student->graduation_status }}</span>
                                </td>
                                {{-- Telah Diakses --}}
                                <td>
                                    <template x-if="editing">
                                        <input type="date" class="form-control" wire:model.defer="editData.accessed_at">
                                    </template>
                                    <span x-show="!editing">{{ $student->accessed_at }}</span>
                                </td>
                                {{-- Action --}}
                                <td>

                                    <template x-if="editing">
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-success btn-sm"
                                                wire:click="saveEdit({{ $student->id }})" @click="editing = false"><i
                                                    class="ti ti-device-floppy"></i> Save</button>
                                            <button class="btn btn-secondary btn-sm" wire:click="cancelEdit"
                                                @click="editing = false"><i class="ti ti-letter-x"></i></button>
                                        </div>
                                    </template>
                                    <template x-if="!editing">

                                        <form method="POST" action="{{ route('graduation.pdf') }}" target="_blank">
                                            @csrf
                                            <input type="hidden" name="nisn" value="{{ $student->nisn }}">
                                            <input type="hidden" name="password"
                                                value="{{ date('d-m-Y', strtotime($student->birth_date)) }}">
                                            <div class="d-flex justify-content-between gap-2">
                                                <button type="submit" class="btn btn-link"><i class="ti ti-printer"></i>
                                                </button>
                                                <button class="btn btn-link" wire:click="startEdit({{ $student->id }})"
                                                    @click="editing = true"><i class="ti ti-pencil"></i></button>
                                            </div>

                                        </form>
                                    </template>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data siswa.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <div x-show="showImport" wire:ignore x-cloak
        class="modal fade fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
        style="display: none;">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <form wire:submit.prevent="import">
                <div class="modal-header p-4 border-b">
                    <h5 class="modal-title">Import Data</h5>
                    <button type="button" class="btn-close" @click="showImport = false"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="file" class="form-control" wire:model="file" accept=".xls,.xlsx,.csv">
                    @error('file') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="modal-footer p-4 border-t flex justify-end gap-2">
                    <button type="button" class="btn btn-secondary" @click="showImport = false">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>