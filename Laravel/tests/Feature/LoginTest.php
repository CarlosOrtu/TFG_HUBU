<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Usuarios;

class LoginTest extends TestCase
{
    /** @test */
    //Caso de prueba 1
    public function loginCorrectoTest()
    {
        //Accedemos al login de la web
        $response = $this->get('/login')->assertSee('Login');
        $credentials = [
            "email" => "administrador@gmail.com",
            "password" => "1234",
        ];
        //Realizamos la solicitud post con los credenciales definidos anteriormente
        $response = $this->post('/login', $credentials);
        //Comprobamos si se redirige correctamente y que el usuario este autenticado
        $response->assertRedirect('/pacientes');
        $this->assertAuthenticated();
    }

    /** @test */
    //Caso de prueba 2
    public function loginIncorrectoTest()
    {
        //Accedemos al login de la web
        $response = $this->get('/login')->assertSee('Login');
        $response->assertSee('Direccion de correo');
        $credentials = [
            "email" => "administrador@gmail.com",
            "password" => "123456",
        ];
        //Realizamos la solicitud post con los credenciales definidos anteriormente
        $response = $this->post('/login', $credentials);
        //Comprobamos si redirige correctamente, que devuelva un mensaje de error y que el usuario no este autenticado
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    //Caso de prueba 3
    public function loginCorreoNoValido()
    {
        //Accedemos al login de la web
        $response = $this->get('/login')->assertSee('Login');
        $response->assertSee('Direccion de correo');
        $credentials = [
            "email" => "administrador",
            "password" => "1234",
        ];
        //Realizamos la solicitud post con los credenciales definidos anteriormente
        $response = $this->post('/login', $credentials);
        //Comprobamos si redirige correctamente, que devuelva un mensaje de error y que el usuario no este autenticado
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    //Caso de prueba 4
    public function loginCorreoVacio()
    {
        //Accedemos al login de la web
        $response = $this->get('/login')->assertSee('Login');
        $response->assertSee('Direccion de correo');
        $credentials = [
            "email" => "",
            "password" => "123456",
        ];
        //Realizamos la solicitud post con los credenciales definidos anteriormente
        $response = $this->post('/login', $credentials);
        //Comprobamos si redirige correctamente, que devuelva un mensaje de error y que el usuario no este autenticado
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    //Caso de prueba 5
    public function loginContrasenaVacia()
    {
        //Accedemos al login de la web
        $response = $this->get('/login')->assertSee('Login');
        $response->assertSee('Direccion de correo');
        $credentials = [
            "email" => "administrador@gmail.com",
            "password" => "",
        ];
        //Realizamos la solicitud post con los credenciales definidos anteriormente
        $response = $this->post('/login', $credentials);
        //Comprobamos si redirige correctamente, que devuelva un mensaje de error y que el usuario no este autenticado
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }
}
