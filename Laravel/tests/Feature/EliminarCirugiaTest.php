<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Tratamientos;
use Tests\Ini\CrearDatosTest;

class EliminarCirugiaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos la cirugía a eliminar
        $cirugiaAEliminar = new Tratamientos();
        $cirugiaAEliminar->id_tratamiento = 999;
        $cirugiaAEliminar->id_paciente = 999;
        $cirugiaAEliminar->tipo = "Cirugia";
        $cirugiaAEliminar->subtipo = "Bilobectomía";
        $cirugiaAEliminar->fecha_inicio = "1998-05-05";
        $cirugiaAEliminar->save();
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
    public function eliminarCirugiaCorrectaTest()
    {
        //Accedemos la vista cirugía
        $response = $this->get('/paciente/999/tratamientos/cirugia')->assertSee('Cirugía');
        //Realizamos la solicitud delete
        $response = $this->delete('/paciente/999/tratamientos/cirugia/0');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/cirugia');
        //Comprobamos que en la vista cirugía no se ven los datos eliminados
        $paciente = Pacientes::find(999);
        $view = $this->view('cirugia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        //Comprobamos que los datos de la cirugía se han eliminado correctamente
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }
}
