<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Tratamientos;

class EliminarRadioterapiaTest extends TestCase
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
