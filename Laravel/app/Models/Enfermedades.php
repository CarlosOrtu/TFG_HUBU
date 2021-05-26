<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enfermedades extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'enfermedades';	
    protected $primaryKey = 'id_enfermedad';

    public static function obtenerId()
    {
        return 'id_enfermedad';
    }
    
	public function Metastasis()
    {
        return $this->hasMany(Metastasis::class,'id_enfermedad');
    }

    public function Sintomas()
    {
        return $this->hasMany(Sintomas::class,'id_enfermedad');
    }

    public function Biomarcadores()
    {
        return $this->hasMany(Biomarcadores::class,'id_enfermedad');
    }

    public function Pruebas_realizadas()
    {
        return $this->hasMany(Pruebas_realizadas::class,'id_enfermedad');
    }

    public function Tecnicas_realizadas()
    {
        return $this->hasMany(Tecnicas_realizadas::class,'id_enfermedad');
    }

    public function Otros_tumores()
    {
        return $this->hasMany(Otros_tumores::class,'id_enfermedad');
    }
}
