<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;

class CrearReevaluacionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario 
        $paciente = new Pacientes();
        $paciente->id_paciente = 999;
        $paciente->nombre = "PacienteTest";
        $paciente->apellidos = "ApellidosTest";
        $paciente->sexo = "Masculino";
        $paciente->nacimiento = "1999-10-05";
        $paciente->raza = "Asiático";      
        $paciente->profesion = "Peluquero";      
        $paciente->fumador = "Desconocido";      
        $paciente->bebedor = "Desconocido";      
        $paciente->carcinogenos = "Desconocido"; 
        $paciente->ultima_modificacion = date("Y-m-d");
        $paciente->save(); 
        //Creamos la enfermedad
        $enfermedad = new Enfermedades();
        $enfermedad->id_enfermedad = 999;
        $enfermedad->id_paciente = 999;
        $enfermedad->fecha_primera_consulta = "1999-02-02";
        $enfermedad->fecha_diagnostico = "1999-03-03";
        $enfermedad->ECOG = 2;
        $enfermedad->T = 3;
        $enfermedad->T_tamano = 1.0;
        $enfermedad->N = 3;
        $enfermedad->N_afectacion = "Uni ganglionar";
        $enfermedad->M = "1b";
        $enfermedad->num_afec_metas = "1";
        $enfermedad->TNM = "IA2";
        $enfermedad->tipo_muestra = "Biopsia";
        $enfermedad->histologia_tipo = "Sarcomatoide";
        $enfermedad->histologia_subtipo = "Mucinoso";
        $enfermedad->histologia_grado = "Bien diferenciado";
        $enfermedad->tratamiento_dirigido = 1;
        $enfermedad->save();

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
        $usuario = Pacientes::find(999)->delete();
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
