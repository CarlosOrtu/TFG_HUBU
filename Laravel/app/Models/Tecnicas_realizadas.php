<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tecnicas_realizadas extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'tecnicas_realizadas';	
	protected $primaryKey = 'id_tecnica';

	public static function obtenerId()
	{
		return 'id_tecnica';
	}
}
