<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antecedentes_medicos extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'antecedentes_medicos';	
	protected $primaryKey = 'id_antecedente_m';
}
