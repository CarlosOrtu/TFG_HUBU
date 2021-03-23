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
  
    public function getAuthPassword()
    {
        return $this->password;
    }
    
    public function username(){
        return 'email';
    }
    
}