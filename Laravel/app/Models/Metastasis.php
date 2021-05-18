<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metastasis extends Model
{
    use HasFactory;

    public $timestamps = false;
	protected $table = 'metastasis';	
	protected $primaryKey = 'id_metastasis';

	public static function getPrimaryKey()
	{
		return 'id_metastasis';
	}
}
 