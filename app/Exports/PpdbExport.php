<?php

namespace Modules\Schooling\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;
use Modules\Schooling\Models\PpdbRegistration;

class PpdbExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $ppdbPeriodId;

    public function __construct($id)
    {
        $this->ppdbPeriodId = $id;
    }

    public function collection()
    {
        return PpdbRegistration::where('ppdb_period_id', $this->ppdbPeriodId)
            ->with([
                'applicant.parent',
                'applicant.document'
            ])->get()->map(function ($pendaftar) {
                $applicant = $pendaftar->applicant;
                $parentData = $applicant->parent;
                $documents = $applicant->document;

                return [
                    'kode_registrasi' => $pendaftar->registration_code,
                    'status_registrasi' => $pendaftar->status,
                    'tanggal_registrasi' => $pendaftar->registered_at ? \Carbon\Carbon::parse($pendaftar->registered_at)->format('Y-m-d') : '-',
                    'nama_lengkap_siswa' => $applicant->full_name,
                    'nama_panggilan' => $applicant->nickname,
                    'jenis_kelamin' => $applicant->gender,
                    'tempat_lahir' => $applicant->place_of_birth,
                    'tanggal_lahir' => $applicant->date_of_birth,
                    'agama' => $applicant->religion,
                    'alamat_siswa' => $applicant->address,
                    'no_hp_siswa' => $applicant->phone,
                    'keterangan' => $applicant->notes ?? '-',
                    'asal_sekolah' => $applicant->previous_school,
                    'nama_ayah' => $parentData->father_name ?? '-',
                    'nama_ibu' => $parentData->mother_name ?? '-',
                    'pekerjaan_ayah' => $parentData->father_job ?? '-',
                    'pekerjaan_ibu' => $parentData->mother_job ?? '-',
                    'alamat_orang_tua' => $parentData->parent_address ?? '-',
                    'no_hp_orang_tua' => $parentData->parent_phone ?? '-',
                    'dokumen_akta' => $documents->birth_certificate ? 'Ada' : 'Tidak Ada',
                    'dokumen_kk' => $documents->family_card ? 'Ada' : 'Tidak Ada',
                    'dokumen_foto' => $documents->photo ? 'Ada' : 'Tidak Ada',
                    'dokumen_ijazah' => $documents->certificate_pa ? 'Ada' : 'Tidak Ada',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Registrasi',
            'Status Registrasi',
            'Tanggal Registrasi',
            'Nama Lengkap Siswa',
            'Nama Panggilan',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Agama',
            'Alamat Siswa',
            'No. HP Siswa',
            'Keterangan',
            'Asal Sekolah',
            'Nama Ayah',
            'Nama Ibu',
            'Pekerjaan Ayah',
            'Pekerjaan Ibu',
            'Alamat Orang Tua',
            'No. HP Orang Tua',
            'Dokumen Akta',
            'Dokumen KK',
            'Dokumen Foto',
            'Dokumen Ijazah',
        ];
    }
}
