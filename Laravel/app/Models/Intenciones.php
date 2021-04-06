<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intenciones extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'intenciones';	

	public function Farmacos()
    {
        return $this->hasMany(Farmacos::class);
    }
}
