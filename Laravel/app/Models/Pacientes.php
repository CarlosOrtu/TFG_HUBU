<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pacientes extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'pacientes';	
    protected $primaryKey = 'id_paciente';

	public function Antecedentes_oncologicos()
    {
        return $this->hasMany(Antecedentes_oncologicos::class,'id_paciente');
    }

    public function Antecedentes_familiares()
    {
        return $this->hasMany(Antecedentes_familiares::class,'id_paciente');
    }
    public function Reevaluaciones()
    {
        return $this->hasMany(Reevaluaciones::class,'id_paciente');
    }
    public function Seguimientos()
    {
        return $this->hasMany(Seguimientos::class,'id_paciente');
    }
    public function Antecedentes_medicos()
    {
        return $this->hasMany(Antecedentes_medicos::class,'id_paciente');
    }
    public function Tratamientos()
    {
        return $this->hasMany(Tratamientos::class,'id_paciente');
    }
    public function Enfermedad()
    {
        return $this->hasOne(Enfermedad::class,'id_paciente');
    }
    public function Comentarios()
    {
        return $this->hasMany(Comentarios::class,'id_paciente');
    }
}
