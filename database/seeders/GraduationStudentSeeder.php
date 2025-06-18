<?php

namespace Modules\Schooling\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Schooling\Models\GraduationStudent; // Asumsikan model ada di sini
use Carbon\Carbon; // Untuk tanggal

class GraduationStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Hapus data lama jika ada
        GraduationStudent::truncate();

        $totalStudents = 50;
        $failedStudentsCount = 5;

        for ($i = 1; $i <= $totalStudents; $i++) {
            $statusKelulusan = ($i <= $failedStudentsCount) ? 'TIDAK LULUS' : 'LULUS';

            GraduationStudent::create([
                'graduation_year_id' => 1,
                'name' => 'Siswa ' . $i,
                'nisn' => (100000 + $i),
                'graduation_status' => $statusKelulusan,
                'birth_date' => Carbon::now(),
                'accessed_at' => Carbon::now()->subDays(rand(1, 365)),

            ]);
        }

        echo "Seeder GraduationStudentSeeder berhasil dijalankan. Ditambahkan {$totalStudents} siswa (" . ($totalStudents - $failedStudentsCount) . " LULUS, {$failedStudentsCount} TIDAK LULUS).\n";
    }
}
