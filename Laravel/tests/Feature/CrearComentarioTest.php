<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use Tests\Ini\CrearDatosTest;

class CrearComentarioTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();

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
        Pacientes::find(999)->delete();
        parent::tearDown();
    }


    /** @test */
    //Caso de prueba 1
    public function crearComentarioCorrectoTest()
    {
        //Accedemos la vista comentario nuevo
        $response = $this->get('/paciente/999/comentarios/nuevo')->assertSee('Nuevo comentario');
        $comentario = [
            "comentario" => "Esto es un comentario de prueba",
        ];
        //Realizamos la solicitud post con los datos del comentario definidos anteriormente
        $response = $this->post('/paciente/999/comentarios/nuevo', $comentario);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/comentarios/nuevo');
        //Comprobamos que en la vista comentario se ven los datos introducidos
        $paciente = Pacientes::find(999);
        $comentarios = $paciente->Comentarios;
        $comentario = $comentarios[0];
        $view = $this->view('comentarios',['paciente' => $paciente, 'comentario' => $comentario, 'posicion' => 0]);
        $view->assertSee('Esto es un comentario de prueba');
        //Comprobamos que los datos del comentario se han introducido correctamente
        $this->assertTrue($paciente->Comentarios[0]->comentario == "Esto es un comentario de prueba");
    }
}
