<?php

namespace App\Utilidades;

class Encriptacion
{

    private $method = "AES-256-CBC"; 
    private $clave = "3tgiC3NnWnfAdLSjrqmqvby7pDCjFF5Q";

    public function encriptar($dato)
    {
       $ivLength = openssl_cipher_iv_length($this->method);
       $iv = openssl_random_pseudo_bytes($ivLength);
       $datoEncriptado = openssl_encrypt($dato,$this->method,$this->clave,0,$iv);
       $combinacion = $iv.$datoEncriptado;
       $combinacion = base64_encode($combinacion);
       return $combinacion;
    }

    public function desencriptar($datoEncriptadoCombinado)
    {
        $datoEncriptadoCombinado = base64_decode($datoEncriptadoCombinado);
        $ivLength = openssl_cipher_iv_length($this->method);
        $iv = substr($datoEncriptadoCombinado,0,$ivLength);
        $datoEncriptado = substr($datoEncriptadoCombinado,$ivLength,strlen($datoEncriptadoCombinado));
        $datoDesencriptado = openssl_decrypt($datoEncriptado,$this->method,$this->clave,0,$iv);

        return $datoDesencriptado;
    }
}