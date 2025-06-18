<?php

namespace Modules\Schooling\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Schooling\Database\Factories\ParentDataFactory;

class ParentData extends Model
{
    use HasFactory;

    protected $table = 'parents'; // Custom name

    protected $fillable = [
        'applicant_id',
        'father_name',
        'mother_name',
        'father_job',
        'mother_job',
        'parent_address',
        'parent_phone'
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}
