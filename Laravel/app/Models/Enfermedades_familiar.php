<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enfermedades_familiar extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'enfermedades_familiar';	
	protected $primaryKey = 'id_enfermedad_f';

	public static function obtenerId()
	{
		return 'id_enfermedad_f';
	}

}
