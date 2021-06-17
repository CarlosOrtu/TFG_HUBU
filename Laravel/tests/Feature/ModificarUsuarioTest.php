<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Usuarios;


class ModificarUsuarioTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario a modificar
        $usuarioAModificar = new Usuarios();
        $usuarioAModificar->id_usuario = 999;
        $usuarioAModificar->nombre = "Usuario";
        $usuarioAModificar->apellidos = "Apellido1 Apellido2";
        $usuarioAModificar->email = "usuario@gmail.com";
        $usuarioAModificar->contrasena = bcrypt("1234");
        $usuarioAModificar->id_rol = 2;      
        $usuarioAModificar->save(); 
        //Realizamos el login con el administrador para poder acceder a todos las rutas de la web
        $this->get('/login')->assertSee('Login');
        $credentials = [
            "email" => "administrador@gmail.com",
            "password" => "1234",
        ];
        $this->post('/login', $credentials);
    }

    protected function tearDown(): void
    {
        //Eliminamos el usuario
        Usuarios::find(999)->delete();
        parent::tearDown();
    }

    /** @test */
    //Caso de prueba 1
    public function modificarUsuarioCorrectoTest()
    {
        //Accedemos la vista modificarusuario 
        $response = $this->get('/modificar/usuario/999')->assertSee('Modificar usuario');
        $usuario = [
            "nombre" => "UsuarioModificado",
            "apellidos" => "ApellidoModificado Apellido2",
            "correo" => "usuariomodificado@gmail.com",
            "rol" => "2",
        ];
        //Realizamos la solicitud put con los datos del usuario definidos anteriormente
        $response = $this->put('/modificar/usuario/999', $usuario);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/administrar/usuarios');
        //Comprobamos que en la vista usuarios se vea el usuario modificado
        $usuarios = Usuarios::all();
        $response = $this->get('/administrar/usuarios');
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertSee('UsuarioModificado');
        $view->assertSee('ApellidoModificado Apellido2');
        //Comprobamos que el usuario este incluido en la base de datos
        $usuario = Usuarios::find(999);
        $this->assertTrue(!empty($usuario));
        //Comprobamos que los datos del usuario coincidan con los introducidos
        $this->assertTrue($usuario->nombre == "UsuarioModificado");
        $this->assertTrue($usuario->apellidos == "ApellidoModificado Apellido2");
        $this->assertTrue($usuario->email == "usuariomodificado@gmail.com");
        $this->assertTrue($usuario->id_rol == "2");
    }

    /** @test */
    //Caso de prueba 2
    public function modificarUsuarioNombreVacioTest()
    {
        //Accedemos la vista modificarusuario 
        $response = $this->get('/modificar/usuario/999')->assertSee('Modificar usuario');
        $usuario = [
            "nombre" => "",
            "apellidos" => "ApellidoModificado Apellido2",
            "correo" => "usuariomodificado@gmail.com",
            "rol" => "2",
        ];
        //Realizamos la solicitud put con los datos del usuario definidos anteriormente
        $response = $this->put('/modificar/usuario/999', $usuario);
        //Comprobamos que devuelve error en el campo nombre
        $response->assertSessionHasErrors('nombre');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/modificar/usuario/999');
        //Comprobamos que en la vista usuarios no se vea el usuario modificado
        $usuarios = Usuarios::all();
        $response = $this->get('/administrar/usuarios');
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertDontSee('ApellidoModificado Apellido2');
        //Comprobamos que el usuario este incluido en la base de datos
        $usuario = Usuarios::find(999);
        $this->assertTrue(!empty($usuario));
        //Comprobamos que los datos del usuario siguen siendo los mismos
        $this->assertTrue($usuario->nombre == "Usuario");
        $this->assertTrue($usuario->apellidos == "Apellido1 Apellido2");
        $this->assertTrue($usuario->email == "usuario@gmail.com");
        $this->assertTrue($usuario->id_rol == "2");
    }

    /** @test */
    //Caso de prueba 3
    public function modificarUsuarioApellidosVacioTest()
    {
        //Accedemos la vista modificarusuario 
        $response = $this->get('/modificar/usuario/999')->assertSee('Modificar usuario');
        $usuario = [
            "nombre" => "UsuarioModificado",
            "apellidos" => "",
            "correo" => "usuariomodificado@gmail.com",
            "rol" => "2",
        ];
        //Realizamos la solicitud put con los datos del usuario definidos anteriormente
        $response = $this->put('/modificar/usuario/999', $usuario);
        //Comprobamos que devuelve error en el campo nombre
        $response->assertSessionHasErrors('apellidos');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/modificar/usuario/999');
        //Comprobamos que en la vista usuarios no se vea el usuario modificado
        $usuarios = Usuarios::all();
        $response = $this->get('/administrar/usuarios');
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertDontSee('UsuarioModificado');
        //Comprobamos que el usuario este incluido en la base de datos
        $usuario = Usuarios::find(999);
        $this->assertTrue(!empty($usuario));
        //Comprobamos que los datos del usuario siguen siendo los mismos
        $this->assertTrue($usuario->nombre == "Usuario");
        $this->assertTrue($usuario->apellidos == "Apellido1 Apellido2");
        $this->assertTrue($usuario->email == "usuario@gmail.com");
        $this->assertTrue($usuario->id_rol == "2");
    }

    /** @test */
    //Caso de prueba 4
    public function modificarUsuarioCorreoVacioTest()
    {
        //Accedemos la vista modificarusuario 
        $response = $this->get('/modificar/usuario/999')->assertSee('Modificar usuario');
        $usuario = [
            "nombre" => "UsuarioModificado",
            "apellidos" => "ApellidoModificado Apellido2",
            "correo" => "",
            "rol" => "2",
        ];
        //Realizamos la solicitud put con los datos del usuario definidos anteriormente
        $response = $this->put('/modificar/usuario/999', $usuario);
        //Comprobamos que devuelve error en el campo nombre
        $response->assertSessionHasErrors('correo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/modificar/usuario/999');
        //Comprobamos que en la vista usuarios no se vea el usuario modificado
        $usuarios = Usuarios::all();
        $response = $this->get('/administrar/usuarios');
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertDontSee('UsuarioModificado');
        $view->assertDontSee('ApellidoModificado Apellido2');
        //Comprobamos que el usuario este incluido en la base de datos
        $usuario = Usuarios::find(999);
        $this->assertTrue(!empty($usuario));
        //Comprobamos que los datos del usuario siguen siendo los mismos
        $this->assertTrue($usuario->nombre == "Usuario");
        $this->assertTrue($usuario->apellidos == "Apellido1 Apellido2");
        $this->assertTrue($usuario->email == "usuario@gmail.com");
        $this->assertTrue($usuario->id_rol == "2");
    }      

    /** @test */
    //Caso de prueba 5
    public function modificarUsuarioCorreoInvalidoTest()
    {
        //Accedemos la vista modificarusuario 
        $response = $this->get('/modificar/usuario/999')->assertSee('Modificar usuario');
        $usuario = [
            "nombre" => "UsuarioModificado",
            "apellidos" => "ApellidoModificado Apellido2",
            "correo" => "usuariomodificadogmail.com",
            "rol" => "2",
        ];
        //Realizamos la solicitud put con los datos del usuario definidos anteriormente
        $response = $this->put('/modificar/usuario/999', $usuario);
        //Comprobamos que devuelve error en el campo nombre
        $response->assertSessionHasErrors('correo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/modificar/usuario/999');
        //Comprobamos que en la vista usuarios no se vea el usuario modificado
        $usuarios = Usuarios::all();
        $response = $this->get('/administrar/usuarios');
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertDontSee('UsuarioModificado');
        $view->assertDontSee('ApellidoModificado Apellido2');
        //Comprobamos que el usuario este incluido en la base de datos
        $usuario = Usuarios::find(999);
        $this->assertTrue(!empty($usuario));
        //Comprobamos que los datos del usuario siguen siendo los mismos
        $this->assertTrue($usuario->nombre == "Usuario");
        $this->assertTrue($usuario->apellidos == "Apellido1 Apellido2");
        $this->assertTrue($usuario->email == "usuario@gmail.com");
        $this->assertTrue($usuario->id_rol == "2");
    }          

    /** @test */
    //Caso de prueba 6
    public function modificarUsuarioCorreoRepetidoTest()
    {
        //Accedemos la vista modificarusuario 
        $response = $this->get('/modificar/usuario/999')->assertSee('Modificar usuario');
        $usuario = [
            "nombre" => "UsuarioModificado",
            "apellidos" => "ApellidoModificado Apellido2",
            "correo" => "administrador@gmail.com",
            "rol" => "2",
        ];
        //Realizamos la solicitud put con los datos del usuario definidos anteriormente
        $response = $this->put('/modificar/usuario/999', $usuario);
        //Comprobamos que devuelve error en el campo nombre
        $response->assertSessionHasErrors('correo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/modificar/usuario/999');
        //Comprobamos que en la vista usuarios no se vea el usuario modificado
        $usuarios = Usuarios::all();
        $response = $this->get('/administrar/usuarios');
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertDontSee('UsuarioModificado');
        $view->assertDontSee('ApellidoModificado Apellido2');
        //Comprobamos que el usuario este incluido en la base de datos
        $usuario = Usuarios::find(999);
        $this->assertTrue(!empty($usuario));
        //Comprobamos que los datos del usuario siguen siendo los mismos
        $this->assertTrue($usuario->nombre == "Usuario");
        $this->assertTrue($usuario->apellidos == "Apellido1 Apellido2");
        $this->assertTrue($usuario->email == "usuario@gmail.com");
        $this->assertTrue($usuario->id_rol == "2");
    }       
}