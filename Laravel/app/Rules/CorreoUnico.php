<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Auth;
use App\Models\Usuarios;

class CorreoUnico implements Rule
{
    public $idUsuario;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($idUsuario=null)
    {
        $this->idUsuario = $idUsuario;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($attribute != "correo")
            return false;
        $usuario = Usuarios::where('email',$value)->first() ;
        if(empty($this->antecedenteO)){
            return empty($idUsuario);
        }
        return (!empty($usuario) and $usuario->id_usuario == $this->idUsuario) or (empty($usuario));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El correo elegido ya esta en uso';
    }
}
