<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pacientes extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'pacientes';	

	public function Antecedentes_oncologicos()
    {
        return $this->hasMany(Antecedentes_oncologicos::class);
    }

    public function Antecedentes_familiares()
    {
        return $this->hasMany(Antecedentes_familiares::class);
    }
    public function Reevaluaciones()
    {
        return $this->hasMany(Reevaluaciones::class);
    }
    public function Seguimientos()
    {
        return $this->hasMany(Seguimientos::class);
    }
    public function Antecedentes_medicos()
    {
        return $this->hasMany(Antecedentes_medicos::class);
    }
    public function Tratamientos()
    {
        return $this->hasMany(Tratamientos::class);
    }
    public function Enfermedades()
    {
        return $this->hasOne(Enfermedades::class);
    }
}
