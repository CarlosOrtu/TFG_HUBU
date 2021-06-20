<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Tratamientos;
use Tests\Ini\CrearDatosTest;

class EliminarRadioterapiaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos la radioterapia que vamos a eliminar
        $radioterapiaAEliminar = new Tratamientos();
        $radioterapiaAEliminar->id_tratamiento = 999;
        $radioterapiaAEliminar->id_paciente = 999;
        $radioterapiaAEliminar->tipo = "Radioterapia";
        $radioterapiaAEliminar->subtipo = "Paliativa";
        $radioterapiaAEliminar->dosis = 4;
        $radioterapiaAEliminar->localizacion = "Ósea";
        $radioterapiaAEliminar->fecha_inicio = "1998-05-05";
        $radioterapiaAEliminar->fecha_fin = "1998-06-06";
        $radioterapiaAEliminar->save();
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
    public function eliminarRadioterapiaCorrectaTest()
    {
        //Accedemos la vista radioterapia
        $response = $this->get('/paciente/999/tratamientos/radioterapia')->assertSee('Radioterapia');
        //Realizamos la solicitud delete
        $response = $this->delete('/paciente/999/tratamientos/radioterapia/0');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/radioterapia');
        //Comprobamos que en la vista radioterapia no se ven los datos eliminados
        $paciente = Pacientes::find(999);
        $view = $this->view('radioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1998-05-05');
        $view->assertDontSee('1998-06-06');
        //Comprobamos que los datos de la radioterapia se han eliminado correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }
}
