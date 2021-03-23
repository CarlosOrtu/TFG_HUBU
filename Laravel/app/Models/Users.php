<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Users extends Authenticatable
{
    use HasFactory, Notifiable;
    
    public $timestamps = false;
    protected $table = 'users';   
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'id_role',
    ];

    protected $hidden = [
        'password',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }
    
    public function username(){
        return 'email';
    }
    
}