<?php

namespace Modules\Schooling\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Schooling\Models\PpdbRegistration;

class PpdbZip extends Component
{
    public $downloadUrl = '';

    public function generateZip($applicantId)
    {
        $registration = PpdbRegistration::where('applicant_id', $applicantId)
            ->with(['applicant', 'applicant.document'])
            ->firstOrFail();

        $applicant = $registration->applicant;
        $documents = $applicant->ppdbDocuments;

        $sanitizedName = Str::slug($applicant->full_name);
        $zipFileName = $sanitizedName . '.zip';
        $tempDir = 'temp/' . $sanitizedName;

        Storage::disk('local')->deleteDirectory($tempDir);
        Storage::disk('local')->makeDirectory($tempDir);

        $zip = new ZipArchive;
        $zipPath = Storage::disk('local')->path($tempDir . '/' . $zipFileName);

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {

            // 1. Masukkan File Dokumen dengan Nama Baru
            $documentMap = [
                'birth_certificate' => 'akta',
                'family_card' => 'kartu-keluarga',
                'photo' => 'foto',
                'certificate_pa' => 'ijazah-sertifikat',
            ];

            foreach ($documentMap as $field => $newName) {
                if ($documents->$field) {
                    $originalPath = 'public/dokumen/' . $documents->$field;
                    if (Storage::exists($originalPath)) {
                        $extension = pathinfo($documents->$field, PATHINFO_EXTENSION);
                        $newFileName = $sanitizedName . '_' . $newName . '.' . $extension;
                        $zip->addFile(Storage::path($originalPath), $newFileName);
                    }
                }
            }

            // 2. Masukkan File PDF Bukti Registrasi
            $pdfContent = Pdf::loadView('schooling::pdf.registration', compact('registration'))->output();
            $pdfFileName = $sanitizedName . '_bukti-registrasi-' . $registration->registration_code . '.pdf';

            Storage::put($tempDir . '/' . $pdfFileName, $pdfContent);
            $zip->addFile(Storage::disk('local')->path($tempDir . '/' . $pdfFileName), $pdfFileName);

            $zip->close();

            $this->downloadUrl = Storage::disk('local')->url($tempDir . '/' . $zipFileName);

            Storage::delete($tempDir . '/' . $pdfFileName);
        }
    }

    public function render()
    {
        return view('schooling::livewire.ppdb-zip');
    }
}
