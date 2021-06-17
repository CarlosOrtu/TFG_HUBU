<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;

class CrearSintomaTest extends TestCase
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
    public function crearSintomaCorrectoTest()
    {
        //Accedemos la vista sintomas
        $response = $this->get('/paciente/999/enfermedad/sintomas')->assertSee('Datos síntomas');
        $sintoma = [
            "fecha_inicio"=> "1999-05-05",
            "tipo"=> "Asintomático",
        ];
        //Realizamos la solicitud post con los datos del sintoma definidos anteriormente
        $response = $this->post('/paciente/999/enfermedad/sintomas', $sintoma);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/sintomas');
        //Comprobamos que en la vista sintoma se vea los datos modificados correctamente
        $paciente = Pacientes::find(999);
        $view = $this->view('datossintomas', ['paciente' => $paciente]);
        $view->assertSee('1999-05-05');
        //Comprobamos que los datos del sintoma se han introducido correctamente
        $this->assertTrue($paciente->Enfermedades->Sintomas[0]->fecha_inicio == "1999-05-05");
        $this->assertTrue($paciente->Enfermedades->Sintomas[0]->tipo == "Asintomático");
    }

    /** @test */
    //Caso de prueba 2
    public function crearSintomaCampoFechaInicioVacioTest()
    {
        //Accedemos la vista sintomas
        $response = $this->get('/paciente/999/enfermedad/sintomas')->assertSee('Datos síntomas');
        $sintoma = [
            "fecha_inicio"=> "",
            "tipo"=> "Asintomático",
        ];
        //Realizamos la solicitud post con los datos del sintoma definidos anteriormente
        $response = $this->post('/paciente/999/enfermedad/sintomas', $sintoma);
        //Comprobamos que devuelve error SQL 
        $response->assertSessionHas('SQLerror');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/sintomas');
        //Comprobamos que los datos del sintoma no se han introducido
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Enfermedades->Sintomas) == 0);
    }


    /** @test */
    //Caso de prueba 3
    public function crearSintomaCampoFechaInicioPosteriorActualTest()
    {
        //Accedemos la vista sintomas
        $response = $this->get('/paciente/999/enfermedad/sintomas')->assertSee('Datos síntomas');
        $sintoma = [
            "fecha_inicio"=> "2022-05-05",
            "tipo"=> "Asintomático",
        ];
        //Realizamos la solicitud post con los datos del sintoma definidos anteriormente
        $response = $this->post('/paciente/999/enfermedad/sintomas', $sintoma);
        //Comprobamos que devuelve error en el campo fecha inicio 
        $response->assertSessionHasErrors('fecha_inicio');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/sintomas');
        //Comprobamos que en la vista sintomas no se vea los datos del sintoma
        $paciente = Pacientes::find(999);
        $view = $this->view('datossintomas', ['paciente' => $paciente]);
        $view->assertDontSee('2022-05-05');
        //Comprobamos que los datos del sintoma no se han introducido
        $this->assertTrue(count($paciente->Enfermedades->Sintomas) == 0);
    }
}
