<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Varpath extends Model
{
	protected $fillable = [
		'name',
		'value',
		'barangay_id',
	];
    protected $table = 'var_path';
}
