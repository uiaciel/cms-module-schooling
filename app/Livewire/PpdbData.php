<?php

namespace Modules\Schooling\Livewire;

use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Schooling\Exports\PpdbExport;
use Modules\Schooling\Models\PpdbPeriod;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Schooling\Models\Applicant;
use Modules\Schooling\Models\PpdbRegistration;
use Illuminate\Support\Facades\Response;

class PpdbData extends Component
{
    public $registrations = [];
    public $selectedRegistration;
    public $showModal = false;
    public $statusOptions = ['registered', 'verified', 'accepted', 'rejected'];
    public $status;
    public $year;
    public $ppdb;
    public $selectedIds = [];
    public $filter_registration_code = '';
    public $filter_full_name = '';
    public $filter_status = '';
    public $selectAll = false;
    public $bulk_status = '';

    public $totalRegistrasi = 0;
    public $totalVerifikasi = 0;
    public $totalNonVerifikasi = 0;

    public $downloadUrl = '';
    public $zipFileName = '';

    public function mount($year = null)
    {
        $this->ppdb = PpdbPeriod::where('year', $year)->first();
        $this->year = $year;
        $this->filter();

        $this->totalRegistrasi = PpdbRegistration::where('ppdb_period_id', $this->ppdb->id)->count();
        $this->totalVerifikasi = PpdbRegistration::where('ppdb_period_id', $this->ppdb->id)->where('status', 'registered')->count();
        $this->totalNonVerifikasi = PpdbRegistration::where('ppdb_period_id', $this->ppdb->id)->where('status', '!=', 'registered')->count();
    }

    public function filter()
    {
        $query = PpdbRegistration::query()->where('ppdb_period_id', $this->ppdb->id)
            ->with(['applicant.parent', 'applicant.document']);

        if ($this->filter_registration_code) {
            $query->where('registration_code', 'like', '%' . $this->filter_registration_code . '%');
        }
        if ($this->filter_full_name) {
            $query->whereHas('applicant', function ($q) {
                $q->where('full_name', 'like', '%' . $this->filter_full_name . '%');
            });
        }
        if ($this->filter_status) {
            $query->where('status', $this->filter_status);
        }

        $this->registrations = $query->get()->map(function ($item) {
            $arr = $item->toArray();
            $arr['applicant'] = $item->applicant ? (object) $item->applicant->toArray() : null;
            if ($arr['applicant']) {
                $arr['applicant']->parent = $item->applicant->parent ? (object) $item->applicant->parent->toArray() : null;
                $arr['applicant']->document = $item->applicant->document ? (object) $item->applicant->document->toArray() : null;
            }
            return (object) $arr;
        })->values()->toArray();

        $this->selectedIds = []; // Reset selected IDs setelah filter
        $this->selectAll = false; // Reset selectAll setelah filter
    }

    public function resetFilter()
    {
        $this->filter_registration_code = '';
        $this->filter_full_name = '';
        $this->filter_status = '';
        $this->filter();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedIds = collect($this->registrations)->pluck('id')->all();
        } else {
            $this->selectedIds = [];
        }
    }

    public function showDetail($id)
    {
        $this->selectedRegistration = PpdbRegistration::with(['applicant.parent', 'applicant.document'])->find($id);
        $this->status = $this->selectedRegistration->status;
        $this->showModal = true;
    }

    public function downloadZip($applicantId)
    {
        // 1. Ambil Data Pendaftar
        $registration = PpdbRegistration::where('applicant_id', $applicantId)
            ->with(['applicant', 'applicant.document'])
            ->firstOrFail();

        $applicant = $registration->applicant;
        $documents = $applicant->document;

        $sanitizedName = Str::slug($applicant->full_name);
        $zipFileName = $sanitizedName . '.zip';
        $tempDir = 'temp/' . $sanitizedName;

        // 2. Bersihkan dan buat direktori temporary
        Storage::disk('local')->deleteDirectory($tempDir);
        Storage::disk('local')->makeDirectory($tempDir);

        $zip = new ZipArchive;
        $zipPath = Storage::disk('local')->path($tempDir . '/' . $zipFileName);

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // 3. Masukkan File Dokumen dengan Nama Baru
            $documentMap = [
                'birth_certificate' => 'akta',
                'family_card' => 'kartu-keluarga',
                'photo' => 'foto',
                'certificate_pa' => 'ijazah-sertifikat',
            ];

            foreach ($documentMap as $field => $newName) {
                if ($documents->$field) {
                    $dbPath = $documents->$field;

                    if (Storage::disk('local')->exists($dbPath)) {
                        $extension = pathinfo($dbPath, PATHINFO_EXTENSION);
                        $newFileName = $sanitizedName . '_' . $newName . '.' . $extension;

                        // Use Storage::disk('private')->path() to get the absolute path
                        $zip->addFile(Storage::disk('local')->path($dbPath), $newFileName);
                    }
                }
            }

            // 4. Masukkan File PDF Bukti Registrasi
            $pdfContent = Pdf::loadView('schooling::pdf.registration', compact('registration'))->output();
            $pdfFileName = $sanitizedName . '_bukti-registrasi-' . $registration->registration_code . '.pdf';

            // Simpan PDF ke direktori sementara
            Storage::disk('local')->put($tempDir . '/' . $pdfFileName, $pdfContent);
            $zip->addFile(Storage::disk('local')->path($tempDir . '/' . $pdfFileName), $pdfFileName);

            $zip->close();

            // 5. Unduh file dan hapus setelah terkirim
            return Response::download($zipPath)->deleteFileAfterSend(true);
        }

        // Jika proses gagal, kembalikan pesan error
        session()->flash('error', 'Gagal membuat file ZIP.');
        return back();
    }

    public function updateStatus()
    {
        if ($this->selectedRegistration) {
            $this->selectedRegistration->status = $this->status;
            $this->selectedRegistration->save();
            session()->flash('success', 'Status berhasil diubah.');

            $this->closeModal();
            $this->filter(); // Memuat ulang data setelah pembaruan
        } else {
            session()->flash('error', 'Data tidak ditemukan.');
            $this->filter();
        }
    }

    public function bulkUpdateStatus()
    {
        if (empty($this->selectedIds) || empty($this->bulk_status)) {
            session()->flash('error', 'Pilih minimal satu pendaftar dan status yang valid.');
            return;
        }

        PpdbRegistration::whereIn('id', $this->selectedIds)->update([
            'status' => $this->bulk_status
        ]);

        session()->flash('success', count($this->selectedIds) . ' pendaftar berhasil diubah statusnya menjadi ' . $this->bulk_status . '.');
        $this->selectedIds = [];
        $this->selectAll = false;
        $this->bulk_status = '';
        $this->filter();
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function exportExcel($id)
    {
        $tahun = PpdbPeriod::where('id', $id)->first();
        $date = now()->format('Y-m-d');

        return Excel::download(new PpdbExport($id), 'data-pendaftar-' . $tahun->year . '-download_tgl-' . $date . '.xlsx');
    }

    public function render()
    {
        return view('schooling::livewire.ppdb-data');
    }
}
