<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Reevaluaciones;
use Tests\Ini\CrearDatosTest;

class EliminarReevaluacionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Crearmos la reevaluacion a eliminar
        $reeEliminar = new Reevaluaciones();
        $reeEliminar->id_paciente = 999;
        $reeEliminar->id_reevaluacion = 999;
        $reeEliminar->fecha = "1998-05-05";
        $reeEliminar->estado = "Respuesta parcial";
        $reeEliminar->save();
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
    public function eliminarReevaluacionCorrectaTest()
    {
        //Accedemos la vista reevaluacion
        $response = $this->get('/paciente/999/reevaluaciones/modificar/0')->assertSee('Reevaluación 1');
        //Realizamos la solicitud delete
        $response = $this->delete('/paciente/999/reevaluaciones/modificar/0');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/reevaluaciones/nueva');
        //Comprobamos que los datos de la reevaluación se han eliminado correctamente
        $reeEliminada = Reevaluaciones::find(999);
        $this->assertTrue(empty($reeEliminada));
    }
}
