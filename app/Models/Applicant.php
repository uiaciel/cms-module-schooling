<?php

namespace Modules\Schooling\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Schooling\Database\Factories\ApplicantFactory;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'register_id',
        'full_name',
        'nickname',
        'gender',
        'place_of_birth',
        'date_of_birth',
        'religion',
        'address',
        'phone',
        'previous_school'
    ];

    public function ppdbPeriod()
    {
        return $this->belongsTo(PpdbPeriod::class);
    }

    public function parent()
    {
        return $this->hasOne(ParentData::class);
    }

    public function document()
    {
        return $this->hasOne(PpdbDocument::class);
    }

    public function registration()
    {
        return $this->hasOne(PpdbRegistration::class);
    }
}
