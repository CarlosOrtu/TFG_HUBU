<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pruebas_realizadas extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'pruebas_realizadas';	
}
