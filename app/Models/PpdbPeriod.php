<?php

namespace Modules\Schooling\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Schooling\Database\Factories\PpdbPeriodFactory;

class PpdbPeriod extends Model
{
    use HasFactory;

    protected $fillable = ['year', 'start_date', 'end_date', 'description', 'brochure_pdf', 'brochure_img', 'status'];

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }

    public function registrations()
    {
        return $this->hasMany(PpdbRegistration::class);
    }
}
