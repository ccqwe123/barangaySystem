<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodMoral extends Model
{
    protected $fillable = [
        'name',
        'address',
        'purpose',
        'requestor_resident_id',
        'barangay_id',
    ];
    protected $table = 'good_moral';
}
