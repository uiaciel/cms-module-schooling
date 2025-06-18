<?php

namespace Modules\Schooling\Imports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\Schooling\Models\GraduationStudent;

class GraduationImport implements ToModel, WithHeadingRow
{

    protected $graduationYearId;

    public function __construct($graduationYearId)
    {
        $this->graduationYearId = $graduationYearId;
    }

    public function model(array $row)
    {

        $birthDate = null;
        if (!empty($row['birth_date'])) {
            try {
                // Format m/d/Y (misal: 10/4/2012)
                $birthDate = Carbon::createFromFormat('m/d/Y', $row['birth_date'])->format('Y-m-d');
            } catch (\Exception $e) {
                try {
                    // Format d/m/Y (misal: 4/10/2012)
                    $birthDate = Carbon::createFromFormat('d/m/Y', $row['birth_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    // Jika gagal, bisa throw exception agar tidak insert data invalid
                    throw new \Exception("Format tanggal tidak valid pada baris: " . json_encode($row));
                }
            }
        }

        return new GraduationStudent(
            [
                'sk' => $row['sk'],
                'name' => $row['name'],
                'nisn' => (string) $row['nisn'],
                'birth_date' => $birthDate,
                'graduation_status' => $row['graduation_status'],
                'graduation_year_id' => $this->graduationYearId,
            ]
        );
    }
}
