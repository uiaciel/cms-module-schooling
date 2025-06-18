<?php

namespace Modules\Schooling\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Schooling\Database\Factories\PpdbRegistrationFactory;

class PpdbRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'registration_code',
        'status',
        'notes',
        'registered_at',
        'verified_at',
        'accepted_at'
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
