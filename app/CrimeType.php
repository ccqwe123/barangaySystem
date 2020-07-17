<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrimeType extends Model
{
    protected $fillable = [
        'name',
    ];
    protected $table = 'crime_type';
}
