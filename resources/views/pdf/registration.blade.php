{{-- filepath: c:\laragon\www\Test\SuryaCMS\Modules\Schooling\resources\views\pdf\registration.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bukti Pendaftaran PPDB</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 13px;
        }

        .header {
            text-align: center;
        }

        .header img {
            width: 120px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 14px;
            margin-bottom: 18px;
        }

        .main {
            margin-top: 20px;
            margin-right: 40px;
            margin-left: 40px;
        }

        table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        td {
            padding: 6px 8px;
            vertical-align: top;
        }

        .label {
            width: 38%;
            font-weight: bold;
        }

        .section-title {
            font-size: 15px;
            font-weight: bold;
            margin-top: 24px;
            margin-bottom: 8px;
            border-bottom: 1px solid #aaa;
            padding-bottom: 2px;
        }

        .footer {
            position: fixed;
            bottom: 40px;
            width: 100%;
            text-align: center;
            font-size: 12px;
            border-top: 1px solid #aaa;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        @if(isset($setting->logo) && file_exists(public_path($setting->logo)))
        <img src="{{ public_path($setting->logo) }}" alt="Logo Sekolah">
        @endif
        <div class="title">Bukti Pendaftaran PPDB</div>
        <div class="subtitle">{{ $setting->sitename ?? '' }}<br>Tahun: {{ $registration->applicant->ppdbPeriod->year ??
            '' }}</div>
    </div>

    <main class="main">
        <div class="section-title">Informasi Registrasi</div>
        <table>
            <tr>
                <td class="label">Kode Registrasi</td>
                <td>: <strong>{{ $registration->registration_code }}</strong></td>
            </tr>
            <tr>
                <td class="label">Tanggal Daftar</td>
                <td>: {{ $registration->registered_at ?
                    \Carbon\Carbon::parse($registration->registered_at)->format('d-m-Y H:i') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td>: {{ ucfirst($registration->status) }}</td>
            </tr>
            <tr>
                <td class="label">Catatan</td>
                <td>: {{ $registration->notes }}</td>
            </tr>
        </table>

        <div class="section-title">Data Siswa</div>
        <table>
            <tr>
                <td class="label">Nama Lengkap</td>
                <td>: {{ $registration->applicant->full_name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Nama Panggilan</td>
                <td>: {{ $registration->applicant->nickname ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Kelamin</td>
                <td>: {{ $registration->applicant->gender == 'L' ? 'Laki-laki' : ($registration->applicant->gender ==
                    'P' ? 'Perempuan' : '-') }}</td>
            </tr>
            <tr>
                <td class="label">Tempat, Tanggal Lahir</td>
                <td>: {{ $registration->applicant->place_of_birth ?? '-' }}, {{ $registration->applicant->date_of_birth
                    ? \Carbon\Carbon::parse($registration->applicant->date_of_birth)->format('d-m-Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="label">Agama</td>
                <td>: {{ $registration->applicant->religion ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat</td>
                <td>: {{ $registration->applicant->address ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">No. HP</td>
                <td>: {{ $registration->applicant->phone ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Asal Sekolah</td>
                <td>: {{ $registration->applicant->previous_school ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Kebutuhan Khusus</td>
                <td>: {{ $registration->applicant->notes ?? '-' }}</td>
            </tr>
        </table>

        <div class="section-title">Data Orang Tua</div>
        <table>
            <tr>
                <td class="label">Nama Ayah</td>
                <td>: {{ $registration->applicant->parent->father_name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Pekerjaan Ayah</td>
                <td>: {{ $registration->applicant->parent->father_job ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Nama Ibu</td>
                <td>: {{ $registration->applicant->parent->mother_name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Pekerjaan Ibu</td>
                <td>: {{ $registration->applicant->parent->mother_job ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Orang Tua</td>
                <td>: {{ $registration->applicant->parent->parent_address ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">No. Telepon Orang Tua</td>
                <td>: {{ $registration->applicant->parent->parent_phone ?? '-' }}</td>
            </tr>
        </table>

        <div class="section-title">Status Dokumen Upload</div>
        <table>
            <tr>
                <td class="label">Akta Kelahiran</td>
                <td>: {{ ($registration->applicant->document && $registration->applicant->document->birth_certificate) ?
                    'Sudah Terupload' : 'Belum' }}</td>
            </tr>
            <tr>
                <td class="label">Kartu Keluarga</td>
                <td>: {{ ($registration->applicant->document && $registration->applicant->document->family_card) ?
                    'Sudah Terupload' : 'Belum' }}</td>
            </tr>
            <tr>
                <td class="label">Pas Foto</td>
                <td>: {{ ($registration->applicant->document && $registration->applicant->document->photo) ? 'Sudah
                    Terupload' : 'Belum' }}</td>
            </tr>
            <tr>
                <td class="label">Sertifikat Lainnya</td>
                <td>: {{ ($registration->applicant->document && $registration->applicant->document->certificate_pa) ?
                    'Sudah Terupload' : 'Belum' }}</td>
            </tr>
        </table>
    </main>

    {{-- Tambahkan data lain sesuai kebutuhan --}}
</body>

</html>