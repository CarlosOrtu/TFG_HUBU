<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antecedentes_oncologicos extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'antecedentes_oncologicos';	
	protected $primaryKey = 'id_antecedente_o';

	public static function obtenerId()
	{
		return 'id_antecedente_o';
	}

}
