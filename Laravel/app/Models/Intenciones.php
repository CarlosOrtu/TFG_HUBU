<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intenciones extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'intenciones';	
	protected $primaryKey = 'id_intencion';

	public static function obtenerId()
	{
		return 'id_intencion';
	}


	public function Farmacos()
    {
        return $this->hasMany(Farmacos::class,'id_intencion');
    }
}
