<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otros_tumores extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'otros_tumores';	
	protected $primaryKey = 'id_tumor';
}
