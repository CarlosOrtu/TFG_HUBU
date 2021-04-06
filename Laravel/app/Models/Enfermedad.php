<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enfermedades extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'enfermedades';	

	public function Metastasis()
    {
        return $this->hasMany(Metastasis::class);
    }

    public function Sintomas()
    {
        return $this->hasMany(Sintomas::class);
    }

    public function Biomarcadores()
    {
        return $this->hasMany(Biomarcadores::class);
    }

    public function Pruebas_realizadas()
    {
        return $this->hasMany(Pruebas_realizadas::class);
    }

    public function Tecnicas_realizadas()
    {
        return $this->hasMany(Tecnicas_realizadas::class);
    }

    public function Otros_tumores()
    {
        return $this->hasMany(Otros_tumores::class);
    }
}
