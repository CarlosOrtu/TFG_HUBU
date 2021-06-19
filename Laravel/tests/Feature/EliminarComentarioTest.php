<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Comentarios;
use Tests\Ini\CrearDatosTest;

class EliminarComentarioTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos el comentario a eliminar
        $comentarioAEliminar = new Comentarios();
        $comentarioAEliminar->id_paciente = 999;
        $comentarioAEliminar->id_comentario = 999;
        $comentarioAEliminar->comentario = "Este es el comentario a eliminar";
        $comentarioAEliminar->save();
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
    public function eliminarComentarioCorrectoTest()
    {
        //Accedemos la vista comentario nuevo
        $response = $this->get('/paciente/999/comentarios/modificar/0')->assertSee('Comentario 1');
        //Realizamos la solicitud delete
        $response = $this->delete('/paciente/999/comentarios/modificar/0');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/comentarios/nuevo');
        //Comprobamos que los datos del comentario se han eliminado correctamente
        $comentarioEliminado = Comentarios::find(999);
        $this->assertTrue(empty($comentarioEliminado));
    }
}
