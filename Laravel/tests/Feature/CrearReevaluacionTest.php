<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use Tests\Ini\CrearDatosTest;

class CrearReevaluacionTest extends TestCase
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
    public function crearReevaluacionCorrectaTest()
    {
        //Accedemos la vista reevaluacion
        $response = $this->get('/paciente/999/reevaluaciones/nueva')->assertSee('Nueva reevaluación');
        $reevaluacion = [
            "fecha" => "1999-05-05",
            "estado" => "Recaída",
        ];
        //Realizamos la solicitud post con los datos de la reevaluacion definidos anteriormente
        $response = $this->post('/paciente/999/reevaluaciones/nueva', $reevaluacion);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/reevaluaciones/nueva');
        //Comprobamos que en la vista reevaluacion se ven los datos introducidos
        $paciente = Pacientes::find(999);
        $reevaluaciones = $paciente->Reevaluaciones;
        $reevaluacion = $reevaluaciones[0];
        $view = $this->view('reevaluaciones',['paciente' => $paciente, 'reevaluacion' => $reevaluacion, 'posicion' => 0]);
        $view->assertSee('1999-05-05');
        //Comprobamos que los datos de la reevaluacion se han introducido correctamente
        $this->assertTrue($paciente->Reevaluaciones[0]->fecha == "1999-05-05");
        $this->assertTrue($paciente->Reevaluaciones[0]->estado == "Recaída");
    }

    /** @test */
    //Caso de prueba 2
    public function crearReevaluacionFechaVacioTest()
    {
        //Accedemos la vista reevaluacion
        $response = $this->get('/paciente/999/reevaluaciones/nueva')->assertSee('Nueva reevaluación');
        $reevaluacion = [
            "fecha" => "",
            "estado" => "Recaída",
        ];
        //Realizamos la solicitud post con los datos de la reevaluacion definidos anteriormente
        $response = $this->post('/paciente/999/reevaluaciones/nueva', $reevaluacion);
        //Comprobamos si devuelve error en el campo fecha
        $response->assertSessionHasErrors('fecha');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/reevaluaciones/nueva');
        //Comprobamos que los datos de la reevaluacion no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Reevaluaciones) == 0);
    }

    /** @test */
    //Caso de prueba 3
    public function crearReevaluacionFechaPosteriorActualTest()
    {
        //Accedemos la vista reevaluacion
        $response = $this->get('/paciente/999/reevaluaciones/nueva')->assertSee('Nueva reevaluación');
        $reevaluacion = [
            "fecha" => "2022-05-05",
            "estado" => "Recaída",
        ];
        //Realizamos la solicitud post con los datos de la reevaluacion definidos anteriormente
        $response = $this->post('/paciente/999/reevaluaciones/nueva', $reevaluacion);
        //Comprobamos si devuelve error en el campo fecha
        $response->assertSessionHasErrors('fecha');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/reevaluaciones/nueva');
        //Comprobamos que los datos de la reevaluacion no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Reevaluaciones) == 0);
    }
}
