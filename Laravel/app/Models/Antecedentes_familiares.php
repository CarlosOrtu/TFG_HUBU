<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antecedentes_familiares extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'antecedentes_familiares';	
    protected $primaryKey = 'id_antecedente_f';

	public function Enfermedades_familiar()
    {
        return $this->hasMany(Enfermedades_familiar::class,'id_antecedente_f');
    }
}
