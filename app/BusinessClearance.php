<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessClearance extends Model
{
    protected $fillable = [
        'business_name',
        'location',
        'name',
        'address',
        'remarks',
        'status',
        'requestor_resident_id',
        'barangay_id',
    ];
    protected $table = 'business_clearance';
}
