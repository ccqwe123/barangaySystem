<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonsOfInterest extends Model
{
     protected $table = 'blotter_personsofinterest';
    public $timestamps = false;
    protected $fillable =  [
	
		'blotter_id',
		'type',
		'name',
		'address',
		'telephone',
		'age',
		];
	public function blotter()
    {
        return $this->belongsTo(Blotter::class);
    }
}
