<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blotter extends Model
{
     protected $fillable = [
        'provincial_id',
        'municipality_id',
        'type',
        'lupon',
        'date_of_incident',
        'address',
        'time_reported',
        'type_of_crime',
        'specify_crime',
        'case_summary',
        'status',
        'hearing_count',
        'barangay_id',
    ];
    protected $table = 'blotter';
}
