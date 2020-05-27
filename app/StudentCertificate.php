<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentCertificate extends Model
{
    protected $fillable = [
        'school_name',
        'date',
        'student_name',
        'purpose',
        'requestor_resident_id',
        'barangay_id',
    ];
    protected $table = 'student_certificate';
}
