<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BarangayOfficials extends Model
{
    protected $fillable = [
        'name',
        'position',
        'barangay_id'
    ];
    protected $table = 'barangay_officials';
}
