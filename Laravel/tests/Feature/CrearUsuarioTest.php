<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Usuarios;

class CrearUsuarioTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Realizamos el login con el administrador para poder acceder a todos las rutas de la web
        $response = $this->get('/login')->assertSee('Login');
        $credentials = [
            "email" => "administrador@gmail.com",
            "password" => "1234",
        ];
        $response = $this->post('/login', $credentials);
    }

    protected function tearDown(): void
    {
        //Eliminamos el usuario
        $usuario = Usuarios::where('email','usuario@gmail.com');
        if(!empty($usuario))
            $usuario->delete();
        parent::tearDown();
    }

    /** @test */
    //Caso de prueba 1
    public function crearNuevoUsuarioCorrectoTest()
    {
        //Accedemos la vista nuevousuario 
        $response = $this->get('/nuevo/usuario')->assertSee('Añadir usuario');
        $usuario = [
            "nombre" => "UsuarioNombre",
            "apellidos" => "Apellido1 Apellido2",
            "correo" => "usuario@gmail.com",
            "contrasena" => "1234",
            "contrasena_repetir" => "1234",
            "rol" => "2",
        ];
        //Realizamos la solicitud post con los datos del usuario definidos anteriormente
        $response = $this->post('/nuevo/usuario', $usuario);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/administrar/usuarios');
        //Comprobamos que en la vista usuarios se vea el nuevo usuario
        $usuarios = Usuarios::all();
        $response = $this->get('/administrar/usuarios');
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertSee('UsuarioNombre');
        $view->assertSee('Apellido1 Apellido2');
        //Comprobamos que el usuario este incluido en la base de datos
        $usuario = Usuarios::where('email','usuario@gmail.com')->first();
        $this->assertTrue(!empty($usuario));
        //Comprobamos que los datos del usuario coincidan con los introducidos
        $this->assertTrue($usuario->nombre == "UsuarioNombre");
        $this->assertTrue($usuario->apellidos == "Apellido1 Apellido2");
        $this->assertTrue(password_verify("1234", $usuario->contrasena));
        $this->assertTrue($usuario->id_rol == "2");
    }

    /** @test */
    //Caso de prueba 2
    public function crearNuevoUsuarioNombreVacioTest()
    {
        //Accedemos la vista nuevousuario 
        $response = $this->get('/nuevo/usuario')->assertSee('Añadir usuario');
        $usuario = [
            "nombre" => "",
            "apellidos" => "Apellido1 Apellido2",
            "correo" => "usuario@gmail.com",
            "contrasena" => "1234",
            "contrasena_repetir" => "1234",
            "rol" => "2",
        ];
        //Realizamos la solicitud post con los datos del usuario definidos anteriormente
        $response = $this->post('/nuevo/usuario', $usuario);
        //Comprobamos que devuelve error en el campo contrasena
        $response->assertSessionHasErrors('nombre');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/nuevo/usuario');
        //Comprobamos que en la vista usuarios no se vea el nuevo usuario
        $usuarios = Usuarios::all();
        $response = $this->get('/administrar/usuarios');
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertDontSee('UsuarioNombre');
        $view->assertDontSee('Apellido1 Apellido2');
        //Comprobamos que el usuario no este incluido en la base de datos
        $usuario = Usuarios::where('email','usuario@gmail.com')->first();
        $this->assertTrue(empty($usuario));
    }

    /** @test */
    //Caso de prueba 3
    public function crearNuevoUsuarioApellidosVacioTest()
    {
        //Accedemos la vista nuevousuario 
        $response = $this->get('/nuevo/usuario')->assertSee('Añadir usuario');
        $usuario = [
            "nombre" => "UsuarioNombre",
            "apellidos" => "",
            "correo" => "usuario@gmail.com",
            "contrasena" => "1234",
            "contrasena_repetir" => "1234",
            "rol" => "2",
        ];
        //Realizamos la solicitud post con los datos del usuario definidos anteriormente
        $response = $this->post('/nuevo/usuario', $usuario);
        //Comprobamos que devuelve error en el campo contrasena
        $response->assertSessionHasErrors('apellidos');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/nuevo/usuario');
        //Comprobamos que en la vista usuarios se vea el nuevo usuario
        $usuarios = Usuarios::all();
        $response = $this->get('/administrar/usuarios');
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertDontSee('UsuarioNombre');
        $view->assertDontSee('Apellido1 Apellido2');
        //Comprobamos que el usuario este incluido en la base de datos
        $usuario = Usuarios::where('email','usuario@gmail.com')->first();
        $this->assertTrue(empty($usuario));
        //Comprobamos que los datos del usuario coincidan con los introducidos
    }

    /** @test */
    //Caso de prueba 4
    public function crearNuevoUsuarioCorreoVacioTest()
    {
        //Accedemos la vista nuevousuario 
        $response = $this->get('/nuevo/usuario')->assertSee('Añadir usuario');
        $usuario = [
            "nombre" => "UsuarioNombre",
            "apellidos" => "Apellido1 Apellido2",
            "correo" => "",
            "contrasena" => "1234",
            "contrasena_repetir" => "1234",
            "rol" => "2",
        ];
        //Realizamos la solicitud post con los datos del usuario definidos anteriormente
        $response = $this->post('/nuevo/usuario', $usuario);
        //Comprobamos que devuelve error en el campo contrasena
        $response->assertSessionHasErrors('correo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/nuevo/usuario');
        //Comprobamos que en la vista usuarios se vea el nuevo usuario
        $usuarios = Usuarios::all();
        $response = $this->get('/administrar/usuarios');
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertDontSee('UsuarioNombre');
        $view->assertDontSee('Apellido1 Apellido2');
        //Comprobamos que el usuario este incluido en la base de datos
        $usuario = Usuarios::where('nombre','UsuarioNombre')->where('apellidos','Apellido1 Apellido2')->first();
        $this->assertTrue(empty($usuario));
        //Comprobamos que los datos del usuario coincidan con los introducidos
    }

    /** @test */
    //Caso de prueba 5
    public function crearNuevoUsuarioContrasenaVacioTest()
    {
        //Accedemos la vista nuevousuario 
        $response = $this->get('/nuevo/usuario')->assertSee('Añadir usuario');
        $usuario = [
            "nombre" => "UsuarioNombre",
            "apellidos" => "Apellido1 Apellido2",
            "correo" => "usuario@gmail.com",
            "contrasena" => "",
            "contrasena_repetir" => "1234",
            "rol" => "2",
        ];
        //Realizamos la solicitud post con los datos del usuario definidos anteriormente
        $response = $this->post('/nuevo/usuario', $usuario);
        //Comprobamos que devuelve error en el campo contrasena
        $response->assertSessionHasErrors('contrasena');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/nuevo/usuario');
        //Comprobamos que en la vista usuarios se vea el nuevo usuario
        $usuarios = Usuarios::all();
        $response = $this->get('/administrar/usuarios');
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertDontSee('UsuarioNombre');
        $view->assertDontSee('Apellido1 Apellido2');
        //Comprobamos que el usuario este incluido en la base de datos
        $usuario = Usuarios::where('email','usuario@gmail.com')->first();
        $this->assertTrue(empty($usuario));
        //Comprobamos que los datos del usuario coincidan con los introducidos
    }

    /** @test */
    //Caso de prueba 6
    public function crearNuevoUsuarioContrasenaRepetirVacioTest()
    {
        //Accedemos la vista nuevousuario 
        $response = $this->get('/nuevo/usuario')->assertSee('Añadir usuario');
        $usuario = [
            "nombre" => "UsuarioNombre",
            "apellidos" => "Apellido1 Apellido2",
            "correo" => "usuario@gmail.com",
            "contrasena" => "1234",
            "contrasena_repetir" => "",
            "rol" => "2",
        ];
        //Realizamos la solicitud post con los datos del usuario definidos anteriormente
        $response = $this->post('/nuevo/usuario', $usuario);
        //Comprobamos que devuelve error en el campo contrasena
        $response->assertSessionHasErrors('contrasena_repetir');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/nuevo/usuario');
        //Comprobamos que en la vista usuarios se vea el nuevo usuario
        $usuarios = Usuarios::all();
        $response = $this->get('/administrar/usuarios');
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertDontSee('UsuarioNombre');
        $view->assertDontSee('Apellido1 Apellido2');
        //Comprobamos que el usuario este incluido en la base de datos
        $usuario = Usuarios::where('email','usuario@gmail.com')->first();
        $this->assertTrue(empty($usuario));
        //Comprobamos que los datos del usuario coincidan con los introducidos
    }

    /** @test */
    //Caso de prueba 7
    public function crearNuevoUsuarioCorreoIncorrectoTest()
    {
        //Accedemos la vista nuevousuario 
        $response = $this->get('/nuevo/usuario')->assertSee('Añadir usuario');
        $usuario = [
            "nombre" => "UsuarioNombre",
            "apellidos" => "Apellido1 Apellido2",
            "correo" => "usuariogmail.com",
            "contrasena" => "1234",
            "contrasena_repetir" => "1234",
            "rol" => "2",
        ];
        //Realizamos la solicitud post con los datos del usuario definidos anteriormente
        $response = $this->post('/nuevo/usuario', $usuario);
        //Comprobamos que devuelve error en el campo contrasena
        $response->assertSessionHasErrors('correo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/nuevo/usuario');
        //Comprobamos que en la vista usuarios no se vea el nuevo usuario
        $usuarios = Usuarios::all();
        $response = $this->get('/administrar/usuarios');
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertDontSee('UsuarioNombre');
        $view->assertDontSee('Apellido1 Apellido2');
        //Comprobamos que el usuario no este incluido en la base de datos
        $usuario = Usuarios::where('email','usuario@gmail.com')->first();
        $this->assertTrue(empty($usuario));
    }

    /** @test */
    //Caso de prueba 8
    public function crearNuevoUsuarioContrasenasNoCoincidentesTest()
    {
        //Accedemos la vista nuevousuario 
        $response = $this->get('/nuevo/usuario')->assertSee('Añadir usuario');
        $usuario = [
            "nombre" => "UsuarioNombre",
            "apellidos" => "Apellido1 Apellido2",
            "correo" => "usuario@gmail.com",
            "contrasena" => "1234",
            "contrasena_repetir" => "2222",
            "rol" => "2",
        ];
        //Realizamos la solicitud post con los datos del usuario definidos anteriormente
        $response = $this->post('/nuevo/usuario', $usuario);
        //Comprobamos que devuelve error en el campo contrasena
        $response->assertSessionHasErrors('contrasena');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/nuevo/usuario');
        //Comprobamos que en la vista usuarios no se vea el nuevo usuario
        $usuarios = Usuarios::all();
        $response = $this->get('/administrar/usuarios');
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertDontSee('UsuarioNombre');
        $view->assertDontSee('Apellido1 Apellido2');
        //Comprobamos que el usuario no este incluido en la base de datos
        $usuario = Usuarios::where('email','usuario@gmail.com')->first();
        $this->assertTrue(empty($usuario));
    }

    /** @test */
    //Caso de prueba 8
    public function crearNuevoUsuarioCorreoRepetidoTest()
    {
        //Accedemos la vista nuevousuario 
        $response = $this->get('/nuevo/usuario')->assertSee('Añadir usuario');
        $usuario = [
            "nombre" => "UsuarioNombre",
            "apellidos" => "Apellido1 Apellido2",
            "correo" => "administrador@gmail.com",
            "contrasena" => "1234",
            "contrasena_repetir" => "1234",
            "rol" => "2",
        ];
        //Realizamos la solicitud post con los datos del usuario definidos anteriormente
        $response = $this->post('/nuevo/usuario', $usuario);
        //Comprobamos que devuelve error en el campo contrasena
        $response->assertSessionHasErrors('correo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/nuevo/usuario');
        //Comprobamos que en la vista usuarios no se vea el nuevo usuario
        $usuarios = Usuarios::all();
        $response = $this->get('/administrar/usuarios');
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertDontSee('UsuarioNombre');
        $view->assertDontSee('Apellido1 Apellido2');
        //Comprobamos que el usuario no este incluido en la base de datos
        $usuario = Usuarios::where('email','usuario@gmail.com')->first();
        $this->assertTrue(empty($usuario));
    }
}
