<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedad;


class ModificarDatosEnfermedadTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario a modificar
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

        $enfermedadAModificar = new Enfermedad();
        $enfermedadAModificar->id_enfermedad = 999;
        $enfermedadAModificar->id_paciente = 999;
        $enfermedadAModificar->fecha_primera_consulta = "1999-02-02";
        $enfermedadAModificar->fecha_diagnostico = "1999-03-03";
        $enfermedadAModificar->ECOG = 2;
        $enfermedadAModificar->T = 3;
        $enfermedadAModificar->T_tamano = 1.0;
        $enfermedadAModificar->N = 3;
        $enfermedadAModificar->N_afectacion = "Uni ganglionar";
        $enfermedadAModificar->M = "1b";
        $enfermedadAModificar->num_afec_metas = "1";
        $enfermedadAModificar->TNM = "IA2";
        $enfermedadAModificar->tipo_muestra = "Biopsia";
        $enfermedadAModificar->histologia_tipo = "Sarcomatoide";
        $enfermedadAModificar->histologia_subtipo = "Mucinoso";
        $enfermedadAModificar->histologia_grado = "Bien diferenciado";
        $enfermedadAModificar->tratamiento_dirigido = 1;
        $enfermedadAModificar->save();

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
    public function modificarEnfermedadCorrectoTest()
    {
        //Accedemos la vista de los datos del paciente
        $response = $this->get('/paciente/999/enfermedad/datosgenerales')->assertSee('Datos enfermedad');
        $enfermedad = [
            "fecha_primera_consulta"=> "1999-05-05",
            "fecha_diagnostico"=> "1999-06-06",
            "ECOG"=> 1,
            "T"=> 1,
            "T_tamano"=> 2.2,
            "N"=> 1,
            "N_afectacion"=> "Multiestacion",
            "M"=> "0",
            "num_afec_metas"=> "0",
            "TNM"=> "IA1",
            "tipo_muestra"=> "Citología",
            "histologia_tipo"=> "Adenocarcinoma",
            "histologia_subtipo"=> "Acinar",
            "histologia_grado"=> "Mal diferenciado",
            "tratamiento_dirigido"=> 0,
        ];
        //Realizamos la solicitud put con los datos del paciente definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/datosgenerales', $enfermedad);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/datosgenerales');
        //Comprobamos que en la vista paciente se vea los datos modificados correctamente
        $pacientes = Pacientes::all();
        $paciente = Pacientes::find(999);
        $view = $this->view('datosenfermedad', ['paciente' => $paciente]);
        $view->assertSee('1999-05-05');
        $view->assertSee('1999-06-06');
        $view->assertSee('2.2');
        //Comprobamos que los datos del paciente coincidan con los modificados
        $this->assertTrue($paciente->Enfermedad->fecha_primera_consulta == "1999-05-05");
        $this->assertTrue($paciente->Enfermedad->fecha_diagnostico == "1999-06-06");
        $this->assertTrue($paciente->Enfermedad->ECOG == 1);
        $this->assertTrue($paciente->Enfermedad->T == 1);
        $this->assertTrue($paciente->Enfermedad->T_tamano == 2.2);
        $this->assertTrue($paciente->Enfermedad->N == 1);
        $this->assertTrue($paciente->Enfermedad->N_afectacion == "Multiestacion");
        $this->assertTrue($paciente->Enfermedad->M == "0");
        $this->assertTrue($paciente->Enfermedad->num_afec_metas == "0");
        $this->assertTrue($paciente->Enfermedad->TNM == "IA1");
        $this->assertTrue($paciente->Enfermedad->tipo_muestra == "Citología");
        $this->assertTrue($paciente->Enfermedad->histologia_tipo == "Adenocarcinoma");
        $this->assertTrue($paciente->Enfermedad->histologia_subtipo == "Acinar");
        $this->assertTrue($paciente->Enfermedad->histologia_grado == "Mal diferenciado");
        $this->assertTrue($paciente->Enfermedad->tratamiento_dirigido == 0);
    }

    /** @test */
    //Caso de prueba 2
    public function modificarEnfermedadCampoFechaPrimeraConsultaVacioTest()
    {
        //Accedemos la vista de los datos del paciente
        $response = $this->get('/paciente/999/enfermedad/datosgenerales')->assertSee('Datos enfermedad');
        $enfermedad = [
            "fecha_primera_consulta"=> "",
            "fecha_diagnostico"=> "1999-06-06",
            "ECOG"=> 1,
            "T"=> 1,
            "T_tamano"=> 2.2,
            "N"=> 1,
            "N_afectacion"=> "Multiestacion",
            "M"=> "0",
            "num_afec_metas"=> "0",
            "TNM"=> "IA1",
            "tipo_muestra"=> "Citología",
            "histologia_tipo"=> "Adenocarcinoma",
            "histologia_subtipo"=> "Acinar",
            "histologia_grado"=> "Mal diferenciado",
            "tratamiento_dirigido"=> 0,
        ];
        //Realizamos la solicitud put con los datos del paciente definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/datosgenerales', $enfermedad);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/datosgenerales');
        //Comprobamos que devuelve error en el campo fecha primera consulta 
        $response->assertSessionHasErrors('fecha_primera_consulta');
        //Comprobamos que en la vista enfermedad no se vean los datos modificados
        $pacientes = Pacientes::all();
        $paciente = Pacientes::find(999);
        $view = $this->view('datosenfermedad', ['paciente' => $paciente]);
        $view->assertDontSee('1999-06-06');
        $view->assertDontSee('2.2');
        //Comprobamos que los datos de la enfermedad no se hayan actualizado
        $this->assertFalse($paciente->Enfermedad->fecha_primera_consulta == "");
        $this->assertFalse($paciente->Enfermedad->fecha_diagnostico == "1999-06-06");
        $this->assertFalse($paciente->Enfermedad->ECOG == 1);
        $this->assertFalse($paciente->Enfermedad->T == 1);
        $this->assertFalse($paciente->Enfermedad->T_tamano == 2.2);
        $this->assertFalse($paciente->Enfermedad->N == 1);
        $this->assertFalse($paciente->Enfermedad->N_afectacion == "Multiestacion");
        $this->assertFalse($paciente->Enfermedad->M == "0");
        $this->assertFalse($paciente->Enfermedad->num_afec_metas == "0");
        $this->assertFalse($paciente->Enfermedad->TNM == "IA1");
        $this->assertFalse($paciente->Enfermedad->tipo_muestra == "Citología");
        $this->assertFalse($paciente->Enfermedad->histologia_tipo == "Adenocarcinoma");
        $this->assertFalse($paciente->Enfermedad->histologia_subtipo == "Acinar");
        $this->assertFalse($paciente->Enfermedad->histologia_grado == "Mal diferenciado");
        $this->assertFalse($paciente->Enfermedad->tratamiento_dirigido == 0);
    }

    /** @test */
    //Caso de prueba 3
    public function modificarEnfermedadConFechaPrimeraConsultaPosteriorTest()
    {
        //Accedemos la vista de los datos del paciente
        $response = $this->get('/paciente/999/enfermedad/datosgenerales')->assertSee('Datos enfermedad');
        $enfermedad = [
            "fecha_primera_consulta"=> "2022-05-05",
            "fecha_diagnostico"=> "1999-06-06",
            "ECOG"=> 1,
            "T"=> 1,
            "T_tamano"=> 2.2,
            "N"=> 1,
            "N_afectacion"=> "Multiestacion",
            "M"=> "0",
            "num_afec_metas"=> "0",
            "TNM"=> "IA1",
            "tipo_muestra"=> "Citología",
            "histologia_tipo"=> "Adenocarcinoma",
            "histologia_subtipo"=> "Acinar",
            "histologia_grado"=> "Mal diferenciado",
            "tratamiento_dirigido"=> 0,
        ];
        //Realizamos la solicitud put con los datos del paciente definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/datosgenerales', $enfermedad);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/datosgenerales');
        //Comprobamos que devuelve error en el campo fecha primera consulta 
        $response->assertSessionHasErrors('fecha_primera_consulta');
        //Comprobamos que en la vista enfermedad no se vean los datos modificados
        $pacientes = Pacientes::all();
        $paciente = Pacientes::find(999);
        $view = $this->view('datosenfermedad', ['paciente' => $paciente]);
        $view->assertDontSee('2022-05-05');
        $view->assertDontSee('1999-06-06');
        $view->assertDontSee('2.2');
        //Comprobamos que los datos de la enfermedad no se hayan actualizado
        $this->assertFalse($paciente->Enfermedad->fecha_primera_consulta == "2022-05-05");
        $this->assertFalse($paciente->Enfermedad->fecha_diagnostico == "1999-06-06");
        $this->assertFalse($paciente->Enfermedad->ECOG == 1);
        $this->assertFalse($paciente->Enfermedad->T == 1);
        $this->assertFalse($paciente->Enfermedad->T_tamano == 2.2);
        $this->assertFalse($paciente->Enfermedad->N == 1);
        $this->assertFalse($paciente->Enfermedad->N_afectacion == "Multiestacion");
        $this->assertFalse($paciente->Enfermedad->M == "0");
        $this->assertFalse($paciente->Enfermedad->num_afec_metas == "0");
        $this->assertFalse($paciente->Enfermedad->TNM == "IA1");
        $this->assertFalse($paciente->Enfermedad->tipo_muestra == "Citología");
        $this->assertFalse($paciente->Enfermedad->histologia_tipo == "Adenocarcinoma");
        $this->assertFalse($paciente->Enfermedad->histologia_subtipo == "Acinar");
        $this->assertFalse($paciente->Enfermedad->histologia_grado == "Mal diferenciado");
        $this->assertFalse($paciente->Enfermedad->tratamiento_dirigido == 0);
    }

    /** @test */
    //Caso de prueba 4
    public function modificarEnfermedadConFechaDiagnosticoVacioTest()
    {
        //Accedemos la vista de los datos del paciente
        $response = $this->get('/paciente/999/enfermedad/datosgenerales')->assertSee('Datos enfermedad');
        $enfermedad = [
            "fecha_primera_consulta"=> "1999-05-05",
            "fecha_diagnostico"=> "",
            "ECOG"=> 1,
            "T"=> 1,
            "T_tamano"=> 2.2,
            "N"=> 1,
            "N_afectacion"=> "Multiestacion",
            "M"=> "0",
            "num_afec_metas"=> "0",
            "TNM"=> "IA1",
            "tipo_muestra"=> "Citología",
            "histologia_tipo"=> "Adenocarcinoma",
            "histologia_subtipo"=> "Acinar",
            "histologia_grado"=> "Mal diferenciado",
            "tratamiento_dirigido"=> 0,
        ];
        //Realizamos la solicitud put con los datos del paciente definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/datosgenerales', $enfermedad);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/datosgenerales');
        //Comprobamos que devuelve error en el campo fecha dianostivo 
        $response->assertSessionHasErrors('fecha_diagnostico');
        //Comprobamos que en la vista enfermedad no se vean los datos modificados
        $pacientes = Pacientes::all();
        $paciente = Pacientes::find(999);
        $view = $this->view('datosenfermedad', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('2.2');
        //Comprobamos que los datos de la enfermedad no se hayan actualizado
        $this->assertFalse($paciente->Enfermedad->fecha_primera_consulta == "1999-05-05");
        $this->assertFalse($paciente->Enfermedad->fecha_diagnostico == "");
        $this->assertFalse($paciente->Enfermedad->ECOG == 1);
        $this->assertFalse($paciente->Enfermedad->T == 1);
        $this->assertFalse($paciente->Enfermedad->T_tamano == 2.2);
        $this->assertFalse($paciente->Enfermedad->N == 1);
        $this->assertFalse($paciente->Enfermedad->N_afectacion == "Multiestacion");
        $this->assertFalse($paciente->Enfermedad->M == "0");
        $this->assertFalse($paciente->Enfermedad->num_afec_metas == "0");
        $this->assertFalse($paciente->Enfermedad->TNM == "IA1");
        $this->assertFalse($paciente->Enfermedad->tipo_muestra == "Citología");
        $this->assertFalse($paciente->Enfermedad->histologia_tipo == "Adenocarcinoma");
        $this->assertFalse($paciente->Enfermedad->histologia_subtipo == "Acinar");
        $this->assertFalse($paciente->Enfermedad->histologia_grado == "Mal diferenciado");
        $this->assertFalse($paciente->Enfermedad->tratamiento_dirigido == 0);
    }

    /** @test */
    //Caso de prueba 5
    public function modificarEnfermedadConFechaDiagnosticoPosteriorTest()
    {
        //Accedemos la vista de los datos del paciente
        $response = $this->get('/paciente/999/enfermedad/datosgenerales')->assertSee('Datos enfermedad');
        $enfermedad = [
            "fecha_primera_consulta"=> "1999-05-05",
            "fecha_diagnostico"=> "2022-06-06",
            "ECOG"=> 1,
            "T"=> 1,
            "T_tamano"=> 2.2,
            "N"=> 1,
            "N_afectacion"=> "Multiestacion",
            "M"=> "0",
            "num_afec_metas"=> "0",
            "TNM"=> "IA1",
            "tipo_muestra"=> "Citología",
            "histologia_tipo"=> "Adenocarcinoma",
            "histologia_subtipo"=> "Acinar",
            "histologia_grado"=> "Mal diferenciado",
            "tratamiento_dirigido"=> 0,
        ];
        //Realizamos la solicitud put con los datos del paciente definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/datosgenerales', $enfermedad);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/datosgenerales');
        //Comprobamos que devuelve error en el campo fecha dianostivo 
        $response->assertSessionHasErrors('fecha_diagnostico');
        //Comprobamos que en la vista enfermedad no se vean los datos modificados
        $pacientes = Pacientes::all();
        $paciente = Pacientes::find(999);
        $view = $this->view('datosenfermedad', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('2022-06-06');
        $view->assertDontSee('2.2');
        //Comprobamos que los datos de la enfermedad no se hayan actualizado
        $this->assertFalse($paciente->Enfermedad->fecha_primera_consulta == "1999-05-05");
        $this->assertFalse($paciente->Enfermedad->fecha_diagnostico == "2022-06-06");
        $this->assertFalse($paciente->Enfermedad->ECOG == 1);
        $this->assertFalse($paciente->Enfermedad->T == 1);
        $this->assertFalse($paciente->Enfermedad->T_tamano == 2.2);
        $this->assertFalse($paciente->Enfermedad->N == 1);
        $this->assertFalse($paciente->Enfermedad->N_afectacion == "Multiestacion");
        $this->assertFalse($paciente->Enfermedad->M == "0");
        $this->assertFalse($paciente->Enfermedad->num_afec_metas == "0");
        $this->assertFalse($paciente->Enfermedad->TNM == "IA1");
        $this->assertFalse($paciente->Enfermedad->tipo_muestra == "Citología");
        $this->assertFalse($paciente->Enfermedad->histologia_tipo == "Adenocarcinoma");
        $this->assertFalse($paciente->Enfermedad->histologia_subtipo == "Acinar");
        $this->assertFalse($paciente->Enfermedad->histologia_grado == "Mal diferenciado");
        $this->assertFalse($paciente->Enfermedad->tratamiento_dirigido == 0);
    }

    /** @test */
    //Caso de prueba 6
    public function modificarEnfermedadConFechaDiagnosticoAnteriorTest()
    {
        //Accedemos la vista de los datos del paciente
        $response = $this->get('/paciente/999/enfermedad/datosgenerales')->assertSee('Datos enfermedad');
        $enfermedad = [
            "fecha_primera_consulta"=> "1999-05-05",
            "fecha_diagnostico"=> "1998-06-06",
            "ECOG"=> 1,
            "T"=> 1,
            "T_tamano"=> 2.2,
            "N"=> 1,
            "N_afectacion"=> "Multiestacion",
            "M"=> "0",
            "num_afec_metas"=> "0",
            "TNM"=> "IA1",
            "tipo_muestra"=> "Citología",
            "histologia_tipo"=> "Adenocarcinoma",
            "histologia_subtipo"=> "Acinar",
            "histologia_grado"=> "Mal diferenciado",
            "tratamiento_dirigido"=> 0,
        ];
        //Realizamos la solicitud put con los datos del paciente definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/datosgenerales', $enfermedad);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/datosgenerales');
        //Comprobamos que devuelve error en el campo fecha dianostivo 
        $response->assertSessionHasErrors('fecha_diagnostico');
        //Comprobamos que en la vista enfermedad no se vean los datos modificados
        $pacientes = Pacientes::all();
        $paciente = Pacientes::find(999);
        $view = $this->view('datosenfermedad', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1998-06-06');
        $view->assertDontSee('2.2');
        //Comprobamos que los datos de la enfermedad no se hayan actualizado
        $this->assertFalse($paciente->Enfermedad->fecha_primera_consulta == "1999-05-05");
        $this->assertFalse($paciente->Enfermedad->fecha_diagnostico == "1998-06-06");
        $this->assertFalse($paciente->Enfermedad->ECOG == 1);
        $this->assertFalse($paciente->Enfermedad->T == 1);
        $this->assertFalse($paciente->Enfermedad->T_tamano == 2.2);
        $this->assertFalse($paciente->Enfermedad->N == 1);
        $this->assertFalse($paciente->Enfermedad->N_afectacion == "Multiestacion");
        $this->assertFalse($paciente->Enfermedad->M == "0");
        $this->assertFalse($paciente->Enfermedad->num_afec_metas == "0");
        $this->assertFalse($paciente->Enfermedad->TNM == "IA1");
        $this->assertFalse($paciente->Enfermedad->tipo_muestra == "Citología");
        $this->assertFalse($paciente->Enfermedad->histologia_tipo == "Adenocarcinoma");
        $this->assertFalse($paciente->Enfermedad->histologia_subtipo == "Acinar");
        $this->assertFalse($paciente->Enfermedad->histologia_grado == "Mal diferenciado");
        $this->assertFalse($paciente->Enfermedad->tratamiento_dirigido == 0);
    }    

    /** @test */
    //Caso de prueba 7
    public function modificarEnfermedadConTamanoTVacio()
    {
        //Accedemos la vista de los datos del paciente
        $response = $this->get('/paciente/999/enfermedad/datosgenerales')->assertSee('Datos enfermedad');
        $enfermedad = [
            "fecha_primera_consulta"=> "1999-05-05",
            "fecha_diagnostico"=> "1999-06-06",
            "ECOG"=> 1,
            "T"=> 1,
            "T_tamano"=> null,
            "N"=> 1,
            "N_afectacion"=> "Multiestacion",
            "M"=> "0",
            "num_afec_metas"=> "0",
            "TNM"=> "IA1",
            "tipo_muestra"=> "Citología",
            "histologia_tipo"=> "Adenocarcinoma",
            "histologia_subtipo"=> "Acinar",
            "histologia_grado"=> "Mal diferenciado",
            "tratamiento_dirigido"=> 0,
        ];
        //Realizamos la solicitud put con los datos del paciente definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/datosgenerales', $enfermedad);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/datosgenerales');
        //Comprobamos que devuelve error en el campo fecha tamaño de T 
        $response->assertSessionHasErrors('T_tamano');
        //Comprobamos que en la vista enfermedad no se vean los datos modificados
        $pacientes = Pacientes::all();
        $paciente = Pacientes::find(999);
        $view = $this->view('datosenfermedad', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        //Comprobamos que los datos de la enfermedad no se hayan actualizado
        $this->assertFalse($paciente->Enfermedad->fecha_primera_consulta == "1999-05-05");
        $this->assertFalse($paciente->Enfermedad->fecha_diagnostico == "1999-06-06");
        $this->assertFalse($paciente->Enfermedad->ECOG == 1);
        $this->assertFalse($paciente->Enfermedad->T == 1);
        $this->assertFalse($paciente->Enfermedad->T_tamano == null);
        $this->assertFalse($paciente->Enfermedad->N == 1);
        $this->assertFalse($paciente->Enfermedad->N_afectacion == "Multiestacion");
        $this->assertFalse($paciente->Enfermedad->M == "0");
        $this->assertFalse($paciente->Enfermedad->num_afec_metas == "0");
        $this->assertFalse($paciente->Enfermedad->TNM == "IA1");
        $this->assertFalse($paciente->Enfermedad->tipo_muestra == "Citología");
        $this->assertFalse($paciente->Enfermedad->histologia_tipo == "Adenocarcinoma");
        $this->assertFalse($paciente->Enfermedad->histologia_subtipo == "Acinar");
        $this->assertFalse($paciente->Enfermedad->histologia_grado == "Mal diferenciado");
        $this->assertFalse($paciente->Enfermedad->tratamiento_dirigido == 0);
    }     

    /** @test */
    //Caso de prueba 8
    public function modificarEnfermedadConTamanoTNegativo()
    {
        //Accedemos la vista de los datos del paciente
        $response = $this->get('/paciente/999/enfermedad/datosgenerales')->assertSee('Datos enfermedad');
        $enfermedad = [
            "fecha_primera_consulta"=> "1999-05-05",
            "fecha_diagnostico"=> "1999-06-06",
            "ECOG"=> 1,
            "T"=> 1,
            "T_tamano"=> -1.0,
            "N"=> 1,
            "N_afectacion"=> "Multiestacion",
            "M"=> "0",
            "num_afec_metas"=> "0",
            "TNM"=> "IA1",
            "tipo_muestra"=> "Citología",
            "histologia_tipo"=> "Adenocarcinoma",
            "histologia_subtipo"=> "Acinar",
            "histologia_grado"=> "Mal diferenciado",
            "tratamiento_dirigido"=> 0,
        ];
        //Realizamos la solicitud put con los datos del paciente definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/datosgenerales', $enfermedad);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/datosgenerales');
        //Comprobamos que devuelve error en el campo fecha tamaño de T 
        $response->assertSessionHasErrors('T_tamano');
        //Comprobamos que en la vista enfermedad no se vean los datos modificados
        $pacientes = Pacientes::all();
        $paciente = Pacientes::find(999);
        $view = $this->view('datosenfermedad', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        $view->assertDontSee('-1.0');
        //Comprobamos que los datos de la enfermedad no se hayan actualizado
        $this->assertFalse($paciente->Enfermedad->fecha_primera_consulta == "1999-05-05");
        $this->assertFalse($paciente->Enfermedad->fecha_diagnostico == "1999-06-06");
        $this->assertFalse($paciente->Enfermedad->ECOG == 1);
        $this->assertFalse($paciente->Enfermedad->T == 1);
        $this->assertFalse($paciente->Enfermedad->T_tamano == -1.0);
        $this->assertFalse($paciente->Enfermedad->N == 1);
        $this->assertFalse($paciente->Enfermedad->N_afectacion == "Multiestacion");
        $this->assertFalse($paciente->Enfermedad->M == "0");
        $this->assertFalse($paciente->Enfermedad->num_afec_metas == "0");
        $this->assertFalse($paciente->Enfermedad->TNM == "IA1");
        $this->assertFalse($paciente->Enfermedad->tipo_muestra == "Citología");
        $this->assertFalse($paciente->Enfermedad->histologia_tipo == "Adenocarcinoma");
        $this->assertFalse($paciente->Enfermedad->histologia_subtipo == "Acinar");
        $this->assertFalse($paciente->Enfermedad->histologia_grado == "Mal diferenciado");
        $this->assertFalse($paciente->Enfermedad->tratamiento_dirigido == 0);
    }             
}
