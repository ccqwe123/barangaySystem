<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barangay extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'barangay_name',
        'barangay_captain_id'
    ];
    protected $table = 'barangay';
}
