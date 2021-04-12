<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Usuarios;

class ModificarDatosPersonalesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos un usuario sobre el cual vamos a realizar los cambios
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
    public function modificarDatosCorrectoTest()
    {
        //Accedemos la vista datospersonales 
        $response = $this->get('/datos/personales')->assertSee('Modificar datos personales');
        $usuarioModificacion = [
            "nombre" => "UsuarioModificado",
            "apellidos" => "ApellidoModificado Apellido2",
            "correo" => "usuariomodificado@gmail.com",
        ];
        //Realizamos la solicitud put con los datos del usuario definidos anteriormente
        $response = $this->put('/datos/personales', $usuarioModificacion);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/datos/personales');
        //Comprobmos que el usuario este incluido en la base de datos
        $usuario = Usuarios::where('nombre','UsuarioModificado')->first();
        $this->assertTrue(!empty($usuario));
        $this->assertTrue($usuario->apellidos == "ApellidoModificado Apellido2");
        $this->assertTrue($usuario->email == "usuariomodificado@gmail.com");
    }

    /** @test */
    //Caso de prueba 2
    public function modificarDatosNombreVacioTest()
    {
        //Accedemos la vista datospersonales
        $response = $this->get('/datos/personales')->assertSee('Modificar datos personales');
        $usuario = [
            "nombre" => "",
            "apellidos" => "ApellidoModificado Apellido2",
            "correo" => "usuariomodificado@gmail.com",
        ];
        //Realizamos la solicitud put con los datos del usuario definidos anteriormente
        $response = $this->put('/datos/personales', $usuario);
        //Comprobamos que devuelve error en el campo nombre
        $response->assertSessionHasErrors('nombre');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/datos/personales');
        //Comprobmos que el usuario no este incluido en la base de datos
        $usuario = Usuarios::where('nombre','UsuarioModificado')->first();
        $this->assertTrue(empty($usuario));
    }

    /** @test */
    //Caso de prueba 3
    public function modificarDatosApellidoVacioTest()
    {
        //Accedemos la vista datospersonales
        $response = $this->get('/datos/personales')->assertSee('Modificar datos personales');
        $usuario = [
            "nombre" => "UsuarioModificado",
            "apellidos" => "",
            "correo" => "usuariomodificado@gmail.com",
        ];
        //Realizamos la solicitud put con los datos del usuario definidos anteriormente
        $response = $this->put('/datos/personales', $usuario);
        //Comprobamos que devuelve error en el campo nombre
        $response->assertSessionHasErrors('apellidos');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/datos/personales');
        //Comprobmos que el usuario no este incluido en la base de datos
        $usuario = Usuarios::where('nombre','UsuarioModificado')->first();
        $this->assertTrue(empty($usuario));
    }

    /** @test */
    //Caso de prueba 4
    public function modificarDatosCorreoVacioTest()
    {
        //Accedemos la vista datospersonales
        $response = $this->get('/datos/personales')->assertSee('Modificar datos personales');
        $usuario = [
            "nombre" => "UsuarioModificado",
            "apellidos" => "ApellidoModificado Apellido2",
            "correo" => "",
        ];
        //Realizamos la solicitud put con los datos del usuario definidos anteriormente
        $response = $this->put('/datos/personales', $usuario);
        //Comprobamos que devuelve error en el campo nombre
        $response->assertSessionHasErrors('correo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/datos/personales');
        //Comprobmos que el usuario no este incluido en la base de datos
        $usuario = Usuarios::where('nombre','UsuarioModificado')->first();
        $this->assertTrue(empty($usuario));
    }

    /** @test */
    //Caso de prueba 5
    public function modificarDatosCorreoNoValidoTest()
    {
        //Accedemos la vista datospersonales
        $response = $this->get('/datos/personales')->assertSee('Modificar datos personales');
        $usuario = [
            "nombre" => "UsuarioModificado",
            "apellidos" => "ApellidoModificado Apellido2",
            "correo" => "usuariomodificadogmail.com",
        ];
        //Realizamos la solicitud put con los datos del usuario definidos anteriormente
        $response = $this->put('/datos/personales', $usuario);
        //Comprobamos que devuelve error en el campo nombre
        $response->assertSessionHasErrors('correo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/datos/personales');
        //Comprobmos que el usuario no este incluido en la base de datos
        $usuario = Usuarios::where('nombre','UsuarioModificado')->first();
        $this->assertTrue(empty($usuario));
    }

    /** @test */
    //Caso de prueba 6
    public function modificarDatosCorreoRepetidoTest()
    {
        //Accedemos la vista datospersonales
        $response = $this->get('/datos/personales')->assertSee('Modificar datos personales');
        $usuario = [
            "nombre" => "UsuarioModificado",
            "apellidos" => "ApellidoModificado Apellido2",
            "correo" => "administrador@gmail.com",
        ];
        //Realizamos la solicitud put con los datos del usuario definidos anteriormente
        $response = $this->put('/datos/personales', $usuario);
        //Comprobamos que devuelve error en el campo nombre
        $response->assertSessionHasErrors('correo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/datos/personales');
        //Comprobmos que el usuario no este incluido en la base de datos
        $usuario = Usuarios::where('nombre','UsuarioModificado')->first();
        $this->assertTrue(empty($usuario));
    }
}
