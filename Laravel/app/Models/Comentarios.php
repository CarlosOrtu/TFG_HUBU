<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentarios extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'comentarios';	
	protected $primaryKey = 'id_comentario';

	public static function obtenerId()
	{
		return 'id_biomarcador';
	}

}
