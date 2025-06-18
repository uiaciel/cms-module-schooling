<?php

namespace Modules\Schooling\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Schooling\Database\Factories\GraduationStudentFactory;

class GraduationStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'sk',
        'graduation_year_id',
        'name',
        'nisn',
        'birth_date',
        'graduation_status',
        'pdf_path',
    ];

    protected $casts = [
        'birth_date' => 'date',

    ];

    public function graduationYear()
    {
        return $this->belongsTo(GraduationYear::class);
    }
}
