<?php

namespace Modules\Schooling\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Schooling\Database\Factories\GraduationYearFactory;

class GraduationYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'open_date',
        'close_date',
        'status',
    ];

    protected $casts = [
        'open_date' => 'date',
        'close_date' => 'date',
    ];

    public function students()
    {
        return $this->hasMany(GraduationStudent::class);
    }
}
