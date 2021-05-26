<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reevaluaciones extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'reevaluaciones';	
	protected $primaryKey = 'id_reevaluacion';

	public static function obtenerId()
	{
		return 'id_reevaluacion';
	}

}
