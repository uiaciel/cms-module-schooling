<?php

namespace Modules\Schooling\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Schooling\Database\Factories\PpdbDocumentFactory;

class PpdbDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id',
        'birth_certificate',
        'family_card',
        'photo',
        'certificate_pa'
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
