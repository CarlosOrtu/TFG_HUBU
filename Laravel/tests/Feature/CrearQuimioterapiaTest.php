<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedad;

class CrearQuimioterapiaTest extends TestCase
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
        $enfermedad = new Enfermedad();
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
    public function crearQuimioterapiaCorrectaTest()
    {
        //Accedemos la vista quimioterapia
        $response = $this->get('/paciente/999/tratamientos/quimioterapia')->assertSee('Quimioterapia');
        $quimioterapia = [
            "intencion" => "Neoadyuvancia",
            "ensayo_clinico" => "Si",
            "ensayo_clinico_tipo" => "Observacional",
            "ensayo_clinico_fase" => 1,
            "tratamiento_acceso" => 1,
            "tratamiento_fuera" => 1,
            "esquema" => "Monoterapia",
            "administracion" => "Oral",
            "num_ciclos" => 2,
            "primer_ciclo" => "1999-05-05",
            "ultimo_ciclo" => "1999-06-06",
            "tipo_farmaco" => "Inmunoterapia",
            "farmacos" => ["Vinorelbina"],
        ];
        //Realizamos la solicitud post con los datos de la quimioterapia definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/quimioterapia', $quimioterapia);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/quimioterapia');
        //Comprobamos que en la vista quimioterapia se ven los datos introducidos
        $paciente = Pacientes::find(999);
        $view = $this->view('quimioterapia', ['paciente' => $paciente]);
        $view->assertSee('1999-05-05');
        $view->assertSee('1999-06-06');
        //Comprobamos que los datos de la quimioterapia se han introducido correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Tratamientos[0]->tipo == "Quimioterapia");
        $this->assertTrue($paciente->Tratamientos[0]->subtipo == "Neoadyuvancia");
        $this->assertTrue($paciente->Tratamientos[0]->fecha_inicio == "1999-05-05");
        $this->assertTrue($paciente->Tratamientos[0]->fecha_fin == "1999-06-06");
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->ensayo == "Observacional");
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->ensayo_fase == 1);
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->tratamiento_acceso_expandido == 1);
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->tratamiento_fuera_indicacion == 1);
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->esquema == "Monoterapia");
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->modo_administracion == "Oral");
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->tipo_farmaco == "Inmunoterapia");
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->numero_ciclos == 2);
        $this->assertTrue($paciente->Tratamientos[0]->Intenciones->Farmacos[0]->tipo == "Vinorelbina");
    }

    /** @test */
    //Caso de prueba 2
    public function crearQuimioterapiaNumeroCiclosVacioTest()
    {
        //Accedemos la vista quimioterapia
        $response = $this->get('/paciente/999/tratamientos/quimioterapia')->assertSee('Quimioterapia');
        $quimioterapia = [
            "intencion" => "Neoadyuvancia",
            "ensayo_clinico" => "Si",
            "ensayo_clinico_tipo" => "Observacional",
            "ensayo_clinico_fase" => 1,
            "tratamiento_acceso" => 1,
            "tratamiento_fuera" => 1,
            "esquema" => "Monoterapia",
            "administracion" => "Oral",
            "num_ciclos" => "",
            "primer_ciclo" => "1999-05-05",
            "ultimo_ciclo" => "1999-06-06",
            "tipo_farmaco" => "Inmunoterapia",
            "farmacos" => ["Vinorelbina"],
        ];
        //Realizamos la solicitud post con los datos de la quimioterapia definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/quimioterapia', $quimioterapia);
        //Comprobamos si devuelve error en el campo num_ciclos
        $response->assertSessionHasErrors('num_ciclos');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/quimioterapia');
        //Comprobamos que en la vista quimioterapia no ven los datos introducidos
        $paciente = Pacientes::find(999);
        $view = $this->view('quimioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        //Comprobamos que los datos de la quimioterapia no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }

    /** @test */
    //Caso de prueba 3
    public function crearQuimioterapiaNumeroCiclosMenorQueUnoTest()
    {
        //Accedemos la vista quimioterapia
        $response = $this->get('/paciente/999/tratamientos/quimioterapia')->assertSee('Quimioterapia');
        $quimioterapia = [
            "intencion" => "Neoadyuvancia",
            "ensayo_clinico" => "Si",
            "ensayo_clinico_tipo" => "Observacional",
            "ensayo_clinico_fase" => 1,
            "tratamiento_acceso" => 1,
            "tratamiento_fuera" => 1,
            "esquema" => "Monoterapia",
            "administracion" => "Oral",
            "num_ciclos" => 0,
            "primer_ciclo" => "1999-05-05",
            "ultimo_ciclo" => "1999-06-06",
            "tipo_farmaco" => "Inmunoterapia",
            "farmacos" => ["Vinorelbina"],
        ];
        //Realizamos la solicitud post con los datos de la quimioterapia definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/quimioterapia', $quimioterapia);
        //Comprobamos si devuelve error en el campo num_ciclos
        $response->assertSessionHasErrors('num_ciclos');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/quimioterapia');
        //Comprobamos que en la vista quimioterapia no ven los datos introducidos
        $paciente = Pacientes::find(999);
        $view = $this->view('quimioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        //Comprobamos que los datos de la quimioterapia no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }

    /** @test */
    //Caso de prueba 4
    public function crearQuimioterapiaFechaPrimerCicloVacioTest()
    {
        //Accedemos la vista quimioterapia
        $response = $this->get('/paciente/999/tratamientos/quimioterapia')->assertSee('Quimioterapia');
        $quimioterapia = [
            "intencion" => "Neoadyuvancia",
            "ensayo_clinico" => "Si",
            "ensayo_clinico_tipo" => "Observacional",
            "ensayo_clinico_fase" => 1,
            "tratamiento_acceso" => 1,
            "tratamiento_fuera" => 1,
            "esquema" => "Monoterapia",
            "administracion" => "Oral",
            "num_ciclos" => 2,
            "primer_ciclo" => "",
            "ultimo_ciclo" => "1999-06-06",
            "tipo_farmaco" => "Inmunoterapia",
            "farmacos" => ["Vinorelbina"],
        ];
        //Realizamos la solicitud post con los datos de la quimioterapia definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/quimioterapia', $quimioterapia);
        //Comprobamos si devuelve error en el campo primer_ciclo
        $response->assertSessionHasErrors('primer_ciclo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/quimioterapia');
        //Comprobamos que en la vista quimioterapia no ven los datos introducidos
        $paciente = Pacientes::find(999);
        $view = $this->view('quimioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        //Comprobamos que los datos de la quimioterapia no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }

    /** @test */
    //Caso de prueba 5
    public function crearQuimioterapiaFechaUltimoCicloVacioTest()
    {
        //Accedemos la vista quimioterapia
        $response = $this->get('/paciente/999/tratamientos/quimioterapia')->assertSee('Quimioterapia');
        $quimioterapia = [
            "intencion" => "Neoadyuvancia",
            "ensayo_clinico" => "Si",
            "ensayo_clinico_tipo" => "Observacional",
            "ensayo_clinico_fase" => 1,
            "tratamiento_acceso" => 1,
            "tratamiento_fuera" => 1,
            "esquema" => "Monoterapia",
            "administracion" => "Oral",
            "num_ciclos" => 2,
            "primer_ciclo" => "1999-05-05",
            "ultimo_ciclo" => "",
            "tipo_farmaco" => "Inmunoterapia",
            "farmacos" => ["Vinorelbina"],
        ];
        //Realizamos la solicitud post con los datos de la quimioterapia definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/quimioterapia', $quimioterapia);
        //Comprobamos si devuelve error en el campo ultimo_ciclo
        $response->assertSessionHasErrors('ultimo_ciclo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/quimioterapia');
        //Comprobamos que en la vista quimioterapia no ven los datos introducidos
        $paciente = Pacientes::find(999);
        $view = $this->view('quimioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        //Comprobamos que los datos de la quimioterapia no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }

    /** @test */
    //Caso de prueba 6
    public function crearQuimioterapiaFechaPrimerCicloPosteriorFechaUltimoCicloTest()
    {
        //Accedemos la vista quimioterapia
        $response = $this->get('/paciente/999/tratamientos/quimioterapia')->assertSee('Quimioterapia');
        $quimioterapia = [
            "intencion" => "Neoadyuvancia",
            "ensayo_clinico" => "Si",
            "ensayo_clinico_tipo" => "Observacional",
            "ensayo_clinico_fase" => 1,
            "tratamiento_acceso" => 1,
            "tratamiento_fuera" => 1,
            "esquema" => "Monoterapia",
            "administracion" => "Oral",
            "num_ciclos" => 2,
            "primer_ciclo" => "1999-07-07",
            "ultimo_ciclo" => "1999-06-06",
            "tipo_farmaco" => "Inmunoterapia",
            "farmacos" => ["Vinorelbina"],
        ];
        //Realizamos la solicitud post con los datos de la quimioterapia definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/quimioterapia', $quimioterapia);
        //Comprobamos si devuelve error en el campo ultimo_ciclo
        $response->assertSessionHasErrors('ultimo_ciclo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/quimioterapia');
        //Comprobamos que en la vista quimioterapia no ven los datos introducidos
        $paciente = Pacientes::find(999);
        $view = $this->view('quimioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('1999-06-06');
        $view->assertDontSee('1999-07-07');
        //Comprobamos que los datos de la quimioterapia no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }

    /** @test */
    //Caso de prueba 7
    public function crearQuimioterapiaFechaPrimerCicloPosteriorActualTest()
    {
        //Accedemos la vista quimioterapia
        $response = $this->get('/paciente/999/tratamientos/quimioterapia')->assertSee('Quimioterapia');
        $quimioterapia = [
            "intencion" => "Neoadyuvancia",
            "ensayo_clinico" => "Si",
            "ensayo_clinico_tipo" => "Observacional",
            "ensayo_clinico_fase" => 1,
            "tratamiento_acceso" => 1,
            "tratamiento_fuera" => 1,
            "esquema" => "Monoterapia",
            "administracion" => "Oral",
            "num_ciclos" => 2,
            "primer_ciclo" => "2022-07-07",
            "ultimo_ciclo" => "2022-08-08",
            "tipo_farmaco" => "Inmunoterapia",
            "farmacos" => ["Vinorelbina"],
        ];
        //Realizamos la solicitud post con los datos de la quimioterapia definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/quimioterapia', $quimioterapia);
        //Comprobamos si devuelve error en el campo primer_ciclo
        $response->assertSessionHasErrors('primer_ciclo');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/quimioterapia');
        //Comprobamos que en la vista quimioterapia no ven los datos introducidos
        $paciente = Pacientes::find(999);
        $view = $this->view('quimioterapia', ['paciente' => $paciente]);
        $view->assertDontSee('2022-07-07');
        $view->assertDontSee('2022-08-08');
        //Comprobamos que los datos de la quimioterapia no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }
    
}