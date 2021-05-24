<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Tratamientos;
use App\Models\Intenciones;
use App\Models\Farmacos;

class EliminarQuimioterapiaTest extends TestCase
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
        //Creamos el tratamiento de quimioterapia que vamos a eliminar
        $quimiotreapiaAEliminar = new Tratamientos();
        $quimiotreapiaAEliminar->id_tratamiento = 999;
        $quimiotreapiaAEliminar->id_paciente = 999;
        $quimiotreapiaAEliminar->tipo = "Quimioterapia";
        $quimiotreapiaAEliminar->subtipo = "Adyuvancia";
        $quimiotreapiaAEliminar->fecha_inicio = "2000-05-05";
        $quimiotreapiaAEliminar->fecha_fin = "2000-06-06";
        $quimiotreapiaAEliminar->save();
        //Creamos la intención del tratamiento que vamos a eliminar
        $intencionAEliminar = new Intenciones();
        $intencionAEliminar->id_intencion = 999;
        $intencionAEliminar->id_tratamiento = 999;
        $intencionAEliminar->tratamiento_acceso_expandido = 0;
        $intencionAEliminar->tratamiento_fuera_indicacion = 0;
        $intencionAEliminar->esquema = "Combinación";
        $intencionAEliminar->modo_administracion = "Intravenoso";
        $intencionAEliminar->tipo_farmaco = "Quimioterapia";
        $intencionAEliminar->numero_ciclos = 4;
        $intencionAEliminar->save();
        //Creamos los farmacos de la intencio que vamos a eliminar
        $farmacoAEliminar = new Farmacos();
        $farmacoAEliminar->id_farmaco = 999;
        $farmacoAEliminar->id_intencion = 999;
        $farmacoAEliminar->tipo = "Cisplatino";
        $farmacoAEliminar->save();
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
    public function eliminarQuimioterapiaCorrectaTest()
    {
        //Accedemos la vista quimioterapia
        $response = $this->get('/paciente/999/tratamientos/quimioterapia')->assertSee('Quimioterapia');
        //Realizamos la solicitud delete
        $response = $this->delete('/paciente/999/tratamientos/quimioterapia/0');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/quimioterapia');
        //Comprobamos que en la vista quimioterapia no se ven los datos eliminados
        $paciente = Pacientes::find(999);
        $view = $this->view('quimioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        //Comprobamos que los datos de la quimioterapia se han modificado correctamente
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }

}
