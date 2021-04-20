<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuarios extends Authenticatable
{
    use HasFactory, Notifiable;
    
    public $timestamps = false;
    protected $table = 'usuarios';   
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nombre',
        'apellidos',
        'correo',
        'contrasena',
        'id_rol',
    ];

    protected $hidden = [
        'contrasena',
    ];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }
    
    public function username(){
        return 'email';
    }
    
}