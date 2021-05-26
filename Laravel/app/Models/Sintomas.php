<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sintomas extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'sintomas';	
	protected $primaryKey = 'id_sintoma';

	public static function obtenerId()
	{
		return 'id_sintoma';
	}

}
