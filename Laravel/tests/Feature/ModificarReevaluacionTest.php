<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Reevaluaciones;

class ModificarReevaluacionTest extends TestCase
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
        //Crearmos la reevaluacion a modificar
        $reeAModificar = new Reevaluaciones();
        $reeAModificar->id_paciente = 999;
        $reeAModificar->id_reevaluacion = 999;
        $reeAModificar->fecha = "1998-05-05";
        $reeAModificar->estado = "Respuesta parcial";
        $reeAModificar->save();
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
