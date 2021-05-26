<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguimientos extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'seguimientos';
	protected $primaryKey = 'id_seguimiento';

	public static function obtenerId()
	{
		return 'id_seguimiento';
	}
	
}
