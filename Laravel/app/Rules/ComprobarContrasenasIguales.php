<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Usuarios;
use Auth;

class ComprobarContrasenasIguales implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        if($attribute != "contrasena_antigua")
            return false;
        return password_verify($value, Auth::user()->contrasena);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'La contrasena introducida no coincide con la contrasena actual';
    }
}
