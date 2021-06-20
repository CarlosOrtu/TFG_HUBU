<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Reevaluaciones;
use Tests\Ini\CrearDatosTest;

class ModificarReevaluacionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Crearmos la reevaluacion a modificar
        $reeAModificar = new Reevaluaciones();
        $reeAModificar->id_paciente = 999;
        $reeAModificar->id_reevaluacion = 999;
        $reeAModificar->fecha = "1998-05-05";
        $reeAModificar->estado = "Respuesta parcial";
        $reeAModificar->save();
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
    public function modificarReevaluacionCorrectaTest()
    {
        //Accedemos la vista reevaluacion
        $response = $this->get('/paciente/999/reevaluaciones/modificar/0')->assertSee('Reevaluación 1');
        $reevaluacion = [
            "fecha" => "1999-05-05",
            "estado" => "Recaída",
        ];
        //Realizamos la solicitud put con los datos de la reevaluacion definidos anteriormente
        $response = $this->put('/paciente/999/reevaluaciones/modificar/0', $reevaluacion);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/reevaluaciones/modificar/0');
        //Comprobamos que en la vista reevaluacion se ven los datos actualizados
        $paciente = Pacientes::find(999);
        $reevaluaciones = $paciente->Reevaluaciones;
        $reevaluacion = $reevaluaciones[0];
        $view = $this->view('reevaluaciones',['paciente' => $paciente, 'reevaluacion' => $reevaluacion, 'posicion' => 0]);
        $view->assertSee('1999-05-05');
        //Comprobamos que los datos de la reevaluacion se han actualizado correctamente
        $this->assertTrue($paciente->Reevaluaciones[0]->fecha == "1999-05-05");
        $this->assertTrue($paciente->Reevaluaciones[0]->estado == "Recaída");
    }

    /** @test */
    //Caso de prueba 2
    public function modificarReevaluacionFechaVacioTest()
    {
        //Accedemos la vista reevaluacion
        $response = $this->get('/paciente/999/reevaluaciones/modificar/0')->assertSee('Reevaluación 1');
        $reevaluacion = [
            "fecha" => "",
            "estado" => "Recaída",
        ];
        //Realizamos la solicitud put con los datos de la reevaluacion definidos anteriormente
        $response = $this->put('/paciente/999/reevaluaciones/modificar/0', $reevaluacion);
        //Comprobamos si devuelve error en el campo fecha
        $response->assertSessionHasErrors('fecha');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/reevaluaciones/modificar/0');
        //Comprobamos que los datos de la reevaluacion no se han actualizado ç
        $paciente = Pacientes::find(999);
        $this->assertFalse($paciente->Reevaluaciones[0]->fecha == "");
        $this->assertFalse($paciente->Reevaluaciones[0]->estado == "Recaída");
    }

    /** @test */
    //Caso de prueba 3
    public function modificarReevaluacionFechaPosteriorActualTest()
    {
        //Accedemos la vista reevaluacion
        $response = $this->get('/paciente/999/reevaluaciones/modificar/0')->assertSee('Reevaluación 1');
        $reevaluacion = [
            "fecha" => "2022-05-05",
            "estado" => "Recaída",
        ];
        //Realizamos la solicitud put con los datos de la reevaluacion definidos anteriormente
        $response = $this->put('/paciente/999/reevaluaciones/modificar/0', $reevaluacion);
        //Comprobamos si devuelve error en el campo fecha
        $response->assertSessionHasErrors('fecha');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/reevaluaciones/modificar/0');
        //Comprobamos que en la vista reevaluacion no se ven los datos actualizados
        $paciente = Pacientes::find(999);
        $reevaluaciones = $paciente->Reevaluaciones;
        $reevaluacion = $reevaluaciones[0];
        $view = $this->view('reevaluaciones',['paciente' => $paciente, 'reevaluacion' => $reevaluacion, 'posicion' => 0]);
        $view->assertDontSee('2022-05-05');
        //Comprobamos que los datos de la reevaluacion no se han actualizado 
        $this->assertFalse($paciente->Reevaluaciones[0]->fecha == "2022-05-05");
        $this->assertFalse($paciente->Reevaluaciones[0]->estado == "Recaída");
    }
}
