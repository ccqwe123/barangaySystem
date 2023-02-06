<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Residents extends Model
{
    protected $fillable = [
		'first_name',
		'middle_name',
		'last_name',
		'birthdate',
		'citizenship',
		'age',
		'gender',
		'civil_status',
		'mobile_no',
		'citizenship',
		'address',
		'barangay_id',
		'employment_status',
		'occupation',
		'birthplace',
	];
	protected $table = 'residents';
	protected $dates = ['deleted_at'];
	
	public function barangay()
	{
		return $this->belongsTo(Barangay::class);
	}
}
