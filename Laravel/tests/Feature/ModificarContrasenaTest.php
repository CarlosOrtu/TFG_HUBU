<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Usuarios;
use Tests\TestCase;

class ModificarContrasenaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos un usuario sobre el cual vamos a modificar sus datos
        $usuarioAModificar = new Usuarios();
        $usuarioAModificar->id_usuario = 999;
        $usuarioAModificar->nombre = "Usuario";
        $usuarioAModificar->apellidos = "Apellido1 Apellido2";
        $usuarioAModificar->email = "usuario@gmail.com";
        $usuarioAModificar->contrasena = bcrypt("1234");
        $usuarioAModificar->id_rol = 2;      
        $usuarioAModificar->save();  
        //Realizamos el login para poder acceder a todos las rutas de la web
        $response = $this->get('/login')->assertSee('Login');
        $credentials = [
            "email" => "usuario@gmail.com",
            "password" => "1234",
        ];
        $response = $this->post('/login', $credentials);
    }

    protected function tearDown(): void
    {
        //Eliminamos el usuario
        Usuarios::find(999)->delete();
        parent::tearDown();
    }

    /** @test */
    //Caso de prueba 1
    public function modificarContrasenaCorrectoTest()
    {
        //Accedemos la vista datospersonales 
        $response = $this->get('/modificar/contrasena')->assertSee('Modificar contraseña');
        $contrasena = [
            "contrasena_antigua" => "1234",
            "contrasena_nueva" => "1111",
            "contrasena_nueva_repetida" => "1111",
        ];
        //Realizamos la solicitud post con los datos del usuario definidos anteriormente
        $response = $this->put('/modificar/contrasena', $contrasena);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/datos/personales');
        //Comprobmos que la contraseña se ha cambiado correctamente
        $usuario = Usuarios::find(999);
        $this->assertTrue(password_verify("1111", $usuario->contrasena));
    }

    /** @test */
    //Caso de prueba 2
    public function modificarContrasenaContrasenaAntiguaVaciaTest()
    {
        //Accedemos la vista datospersonales
        $response = $this->get('/modificar/contrasena')->assertSee('Modificar contraseña');
        $contrasena = [
            "contrasena_antigua" => "",
            "contrasena_nueva" => "1111",
            "contrasena_nueva_repetida" => "1111",
        ];
        //Realizamos la solicitud post con los datos del usuario definidos anteriormente
        $response = $this->put('/modificar/contrasena', $contrasena);
        //Comprobamos que devuelve error en el campo nombre
        $response->assertSessionHasErrors('contrasena_antigua');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/modificar/contrasena');
        //Comprobmos que la contraseña sigue siendo la misma
        $usuario = Usuarios::find(999);
        $this->assertTrue(password_verify("1234", $usuario->contrasena));
    }

    /** @test */
    //Caso de prueba 3
    public function modificarContrasenaContrasenaNuevaVaciaTest()
    {
        //Accedemos la vista datospersonales
        $response = $this->get('/modificar/contrasena')->assertSee('Modificar contraseña');
        $contrasena = [
            "contrasena_antigua" => "1234",
            "contrasena_nueva" => "",
            "contrasena_nueva_repetida" => "1111",
        ];
        //Realizamos la solicitud post con los datos del usuario definidos anteriormente
        $response = $this->put('/modificar/contrasena', $contrasena);
        //Comprobamos que devuelve error en el campo nombre
        $response->assertSessionHasErrors('contrasena_nueva');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/modificar/contrasena');
        //Comprobmos que la contraseña sigue siendo la misma
        $usuario = Usuarios::find(999);
        $this->assertTrue(password_verify("1234", $usuario->contrasena));
    }

    /** @test */
    //Caso de prueba 4
    public function modificarContrasenaContrasenaNuevaRepetidaVaciaTest()
    {
        //Accedemos la vista datospersonales
        $response = $this->get('/modificar/contrasena')->assertSee('Modificar contraseña');
        $contrasena = [
            "contrasena_antigua" => "1234",
            "contrasena_nueva" => "1111",
            "contrasena_nueva_repetida" => "",
        ];
        //Realizamos la solicitud post con los datos del usuario definidos anteriormente
        $response = $this->put('/modificar/contrasena', $contrasena);
        //Comprobamos que devuelve error en el campo nombre
        $response->assertSessionHasErrors('contrasena_nueva_repetida');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/modificar/contrasena');
        //Comprobmos que la contraseña sigue siendo la misma
        $usuario = Usuarios::find(999);
        $this->assertTrue(password_verify("1234", $usuario->contrasena));
    }

    /** @test */
    //Caso de prueba 5
    public function modificarContrasenaContrasenaAntiguaErroneaTest()
    {
        //Accedemos la vista datospersonales
        $response = $this->get('/modificar/contrasena')->assertSee('Modificar contraseña');
        $contrasena = [
            "contrasena_antigua" => "2222",
            "contrasena_nueva" => "1111",
            "contrasena_nueva_repetida" => "1111",
        ];
        //Realizamos la solicitud post con los datos del usuario definidos anteriormente
        $response = $this->put('/modificar/contrasena', $contrasena);
        //Comprobamos que devuelve error en el campo nombre
        $response->assertSessionHasErrors('contrasena_antigua');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/modificar/contrasena');
        //Comprobmos que la contraseña sigue siendo la misma
        $usuario = Usuarios::find(999);
        $this->assertTrue(password_verify("1234", $usuario->contrasena));
    }

    /** @test */
    //Caso de prueba 6
    public function modificarContrasenaContrasenasNuevasNoCoincidentesTest()
    {
        //Accedemos la vista datospersonales
        $response = $this->get('/modificar/contrasena')->assertSee('Modificar contraseña');
        $contrasena = [
            "contrasena_antigua" => "1234",
            "contrasena_nueva" => "2222",
            "contrasena_nueva_repetida" => "1111",
        ];
        //Realizamos la solicitud post con los datos del usuario definidos anteriormente
        $response = $this->put('/modificar/contrasena', $contrasena);
        //Comprobamos que devuelve error en el campo nombre
        $response->assertSessionHasErrors('contrasena_nueva');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/modificar/contrasena');
        //Comprobmos que la contraseña sigue siendo la misma
        $usuario = Usuarios::find(999);
        $this->assertTrue(password_verify("1234", $usuario->contrasena));
    }
}
