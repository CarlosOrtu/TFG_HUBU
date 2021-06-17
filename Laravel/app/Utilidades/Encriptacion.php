<?php

namespace App\Utilidades;

class Encriptacion
{

    private $method = "AES-256-CBC"; 
    private $clave = "3tgiC3NnWnfAdLSjrqmqvby7pDCjFF5Q";

    public function encriptar($dato)
    {
       $ivLength = openssl_cipher_iv_length($this->method);
       $ivValue = openssl_random_pseudo_bytes($ivLength);
       $datoEncriptado = openssl_encrypt($dato,$this->method,$this->clave,0,$ivValue);
       $combinacion = $ivValue.$datoEncriptado;
       $combinacion = base64_encode($combinacion);
       return $combinacion;
    }

    public function desencriptar($datoEncriptadoIv)
    {
        $datoEncriptadoIv = base64_decode($datoEncriptadoIv);
        $ivLength = openssl_cipher_iv_length($this->method);
        $ivValue = substr($datoEncriptadoIv,0,$ivLength);
        $datoEncriptado = substr($datoEncriptadoIv,$ivLength,strlen($datoEncriptadoIv));
        $datoDesencriptado = openssl_decrypt($datoEncriptado,$this->method,$this->clave,0,$ivValue);

        return $datoDesencriptado;
    }
}