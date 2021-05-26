<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biomarcadores extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'biomarcadores';	
	protected $primaryKey = 'id_biomarcador';

	public static function obtenerId()
	{
		return 'id_biomarcador';
	}

}
