<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Usuarios;


class EliminarUsuarioTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos un usuario el cual vamos a eliminar
        $usuarioAModificar = new Usuarios();
        $usuarioAModificar->id_usuario = 999;
        $usuarioAModificar->nombre = "UsuarioNombre";
        $usuarioAModificar->apellidos = "Apellido1 Apellido2";
        $usuarioAModificar->email = "usuario@gmail.com";
        $usuarioAModificar->contrasena = bcrypt("1234");
        $usuarioAModificar->id_rol = 1;      
        $usuarioAModificar->save();  
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
        parent::tearDown();
    }

    /** @test */
    //Caso de prueba 1
    public function eliminarUsuarioCorrectoTest()
    {
        //Accedemos la vista administrarusuarios 
        $response = $this->get('/administrar/usuarios')->assertSee('Listado de usuarios');
        //Comprobamos que se ve el usuario
        $usuarios = Usuarios::all();
        
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertSee('999');
        $view->assertSee('UsuarioNombre');
        $view->assertSee('Apellido1 Apellido2');
        //Realizamos la solicitud delete
        $response = $this->delete('/eliminar/usuario/999');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/administrar/usuarios');
        //Comprobamos que el usuario este eliminado de la base de datos
        $usuario = Usuarios::find(999);
        $this->assertTrue(empty($usuario)); 
        //Comprobamos que el usuario no se vea
        $usuarios = Usuarios::all();
        $response = $this->get('/administrar/usuarios');
        $view = $this->view('usuarios', ['usuarios' => $usuarios]);
        $view->assertDontSee('999');
        $view->assertDontSee('UsuarioNombre');
        $view->assertDontSee('Apellido1 Apellido2');
    }
}

