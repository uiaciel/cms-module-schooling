{{-- filepath: Modules/Schooling/resources/views/pdf/registration.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bukti Pendaftaran PPDB</title>
    <style>
        body {
            font-family: sans-serif;
        }
    </style>
</head>

<body>
    <h2>Bukti Pendaftaran PPDB</h2>
    <p>Kode Registrasi: <strong>{{ $registration->registration_code }}</strong></p>
    <p>Nama: {{ $registration->applicant->full_name }}</p>
    <p>Status: {{ $registration->status }}</p>
    <p>Catatan: {{ $registration->notes }}</p>
    <p>Tanggal Daftar: {{ $registration->registered_at }}</p>
    {{-- Tambahkan data lain sesuai kebutuhan --}}
</body>

</html>