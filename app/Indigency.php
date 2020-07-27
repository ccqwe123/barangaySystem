<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Indigency extends Model
{
    protected $fillable = [
        'name',
        'address',
        'purpose',
        'requestor_resident_id',
        'barangay_id',
    ];
    protected $table = 'barangay_indigency';
}
