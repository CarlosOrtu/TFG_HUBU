<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tratamientos extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'tratamientos';

	public function Intenciones()
    {
        return $this->hasOne(Intenciones::class);
    }	
}