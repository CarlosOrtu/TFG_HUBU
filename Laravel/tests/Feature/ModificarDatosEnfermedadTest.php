<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use Tests\Ini\CrearDatosTest;

class ModificarDatosEnfermedadTest extends TestCase
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
        //Realizamos la solicitud put con los datos de la enfermad definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/datosgenerales', $enfermedad);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/datosgenerales');
        //Comprobamos que en la vista enfermedad se vea los datos modificados correctamente
        $paciente = Pacientes::find(999);
        $view = $this->view('datosenfermedad', ['paciente' => $paciente]);
        $view->assertSee('1999-05-05');
        $view->assertSee('1999-06-06');
        $view->assertSee('2.2');
        //Comprobamos que los datos de la enfermedad coincidan con los modificados
        $this->assertTrue($paciente->Enfermedades->fecha_primera_consulta == "1999-05-05");
        $this->assertTrue($paciente->Enfermedades->fecha_diagnostico == "1999-06-06");
        $this->assertTrue($paciente->Enfermedades->ECOG == 1);
        $this->assertTrue($paciente->Enfermedades->T == 1);
        $this->assertTrue($paciente->Enfermedades->T_tamano == 2.2);
        $this->assertTrue($paciente->Enfermedades->N == 1);
        $this->assertTrue($paciente->Enfermedades->N_afectacion == "Multiestacion");
        $this->assertTrue($paciente->Enfermedades->M == "0");
        $this->assertTrue($paciente->Enfermedades->num_afec_metas == "0");
        $this->assertTrue($paciente->Enfermedades->TNM == "IA1");
        $this->assertTrue($paciente->Enfermedades->tipo_muestra == "Citología");
        $this->assertTrue($paciente->Enfermedades->histologia_tipo == "Adenocarcinoma");
        $this->assertTrue($paciente->Enfermedades->histologia_subtipo == "Acinar");
        $this->assertTrue($paciente->Enfermedades->histologia_grado == "Mal diferenciado");
        $this->assertTrue($paciente->Enfermedades->tratamiento_dirigido == 0);
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
        //Realizamos la solicitud put con los datos de la enfermedad definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/datosgenerales', $enfermedad);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/datosgenerales');
        //Comprobamos que devuelve error en el campo fecha primera consulta 
        $response->assertSessionHasErrors('fecha_primera_consulta');
        //Comprobamos que en la vista enfermedad no se vean los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('datosenfermedad', ['paciente' => $paciente]);
        $view->assertDontSee('1999-06-06');
        $view->assertDontSee('2.2');
        //Comprobamos que los datos de la enfermedad no se hayan actualizado
        $this->assertFalse($paciente->Enfermedades->fecha_primera_consulta == "");
        $this->assertFalse($paciente->Enfermedades->fecha_diagnostico == "1999-06-06");
        $this->assertFalse($paciente->Enfermedades->ECOG == 1);
        $this->assertFalse($paciente->Enfermedades->T == 1);
        $this->assertFalse($paciente->Enfermedades->T_tamano == 2.2);
        $this->assertFalse($paciente->Enfermedades->N == 1);
        $this->assertFalse($paciente->Enfermedades->N_afectacion == "Multiestacion");
        $this->assertFalse($paciente->Enfermedades->M == "0");
        $this->assertFalse($paciente->Enfermedades->num_afec_metas == "0");
        $this->assertFalse($paciente->Enfermedades->TNM == "IA1");
        $this->assertFalse($paciente->Enfermedades->tipo_muestra == "Citología");
        $this->assertFalse($paciente->Enfermedades->histologia_tipo == "Adenocarcinoma");
        $this->assertFalse($paciente->Enfermedades->histologia_subtipo == "Acinar");
        $this->assertFalse($paciente->Enfermedades->histologia_grado == "Mal diferenciado");
        $this->assertFalse($paciente->Enfermedades->tratamiento_dirigido == 0);
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
        //Realizamos la solicitud put con los datos de la enfermedad definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/datosgenerales', $enfermedad);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/datosgenerales');
        //Comprobamos que devuelve error en el campo fecha primera consulta 
        $response->assertSessionHasErrors('fecha_primera_consulta');
        //Comprobamos que en la vista enfermedad no se vean los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('datosenfermedad', ['paciente' => $paciente]);
        $view->assertDontSee('2022-05-05');
        $view->assertDontSee('1999-06-06');
        $view->assertDontSee('2.2');
        //Comprobamos que los datos de la enfermedad no se hayan actualizado
        $this->assertFalse($paciente->Enfermedades->fecha_primera_consulta == "2022-05-05");
        $this->assertFalse($paciente->Enfermedades->fecha_diagnostico == "1999-06-06");
        $this->assertFalse($paciente->Enfermedades->ECOG == 1);
        $this->assertFalse($paciente->Enfermedades->T == 1);
        $this->assertFalse($paciente->Enfermedades->T_tamano == 2.2);
        $this->assertFalse($paciente->Enfermedades->N == 1);
        $this->assertFalse($paciente->Enfermedades->N_afectacion == "Multiestacion");
        $this->assertFalse($paciente->Enfermedades->M == "0");
        $this->assertFalse($paciente->Enfermedades->num_afec_metas == "0");
        $this->assertFalse($paciente->Enfermedades->TNM == "IA1");
        $this->assertFalse($paciente->Enfermedades->tipo_muestra == "Citología");
        $this->assertFalse($paciente->Enfermedades->histologia_tipo == "Adenocarcinoma");
        $this->assertFalse($paciente->Enfermedades->histologia_subtipo == "Acinar");
        $this->assertFalse($paciente->Enfermedades->histologia_grado == "Mal diferenciado");
        $this->assertFalse($paciente->Enfermedades->tratamiento_dirigido == 0);
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
        //Realizamos la solicitud put con los datos de la enfermedad definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/datosgenerales', $enfermedad);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/datosgenerales');
        //Comprobamos que devuelve error en el campo fecha dianostivo 
        $response->assertSessionHasErrors('fecha_diagnostico');
        //Comprobamos que en la vista enfermedad no se vean los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('datosenfermedad', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('2.2');
        //Comprobamos que los datos de la enfermedad no se hayan actualizado
        $this->assertFalse($paciente->Enfermedades->fecha_primera_consulta == "1999-05-05");
        $this->assertFalse($paciente->Enfermedades->fecha_diagnostico == "");
        $this->assertFalse($paciente->Enfermedades->ECOG == 1);
        $this->assertFalse($paciente->Enfermedades->T == 1);
        $this->assertFalse($paciente->Enfermedades->T_tamano == 2.2);
        $this->assertFalse($paciente->Enfermedades->N == 1);
        $this->assertFalse($paciente->Enfermedades->N_afectacion == "Multiestacion");
        $this->assertFalse($paciente->Enfermedades->M == "0");
        $this->assertFalse($paciente->Enfermedades->num_afec_metas == "0");
        $this->assertFalse($paciente->Enfermedades->TNM == "IA1");
        $this->assertFalse($paciente->Enfermedades->tipo_muestra == "Citología");
        $this->assertFalse($paciente->Enfermedades->histologia_tipo == "Adenocarcinoma");
        $this->assertFalse($paciente->Enfermedades->histologia_subtipo == "Acinar");
        $this->assertFalse($paciente->Enfermedades->histologia_grado == "Mal diferenciado");
        $this->assertFalse($paciente->Enfermedades->tratamiento_dirigido == 0);
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
        //Realizamos la solicitud put con los datos de la enfermedad definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/datosgenerales', $enfermedad);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/datosgenerales');
        //Comprobamos que devuelve error en el campo fecha dianostivo 
        $response->assertSessionHasErrors('fecha_diagnostico');
        //Comprobamos que en la vista enfermedad no se vean los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('datosenfermedad', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('2022-06-06');
        $view->assertDontSee('2.2');
        //Comprobamos que los datos de la enfermedad no se hayan actualizado
        $this->assertFalse($paciente->Enfermedades->fecha_primera_consulta == "1999-05-05");
        $this->assertFalse($paciente->Enfermedades->fecha_diagnostico == "2022-06-06");
        $this->assertFalse($paciente->Enfermedades->ECOG == 1);
        $this->assertFalse($paciente->Enfermedades->T == 1);
        $this->assertFalse($paciente->Enfermedades->T_tamano == 2.2);
        $this->assertFalse($paciente->Enfermedades->N == 1);
        $this->assertFalse($paciente->Enfermedades->N_afectacion == "Multiestacion");
        $this->assertFalse($paciente->Enfermedades->M == "0");
        $this->assertFalse($paciente->Enfermedades->num_afec_metas == "0");
        $this->assertFalse($paciente->Enfermedades->TNM == "IA1");
        $this->assertFalse($paciente->Enfermedades->tipo_muestra == "Citología");
        $this->assertFalse($paciente->Enfermedades->histologia_tipo == "Adenocarcinoma");
        $this->assertFalse($paciente->Enfermedades->histologia_subtipo == "Acinar");
        $this->assertFalse($paciente->Enfermedades->histologia_grado == "Mal diferenciado");
        $this->assertFalse($paciente->Enfermedades->tratamiento_dirigido == 0);
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
        //Realizamos la solicitud put con los datos de la enfermedad definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/datosgenerales', $enfermedad);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/datosgenerales');
        //Comprobamos que devuelve error en el campo fecha dianostivo 
        $response->assertSessionHasErrors('fecha_diagnostico');
        //Comprobamos que en la vista enfermedad no se vean los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('datosenfermedad', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1998-06-06');
        $view->assertDontSee('2.2');
        //Comprobamos que los datos de la enfermedad no se hayan actualizado
        $this->assertFalse($paciente->Enfermedades->fecha_primera_consulta == "1999-05-05");
        $this->assertFalse($paciente->Enfermedades->fecha_diagnostico == "1998-06-06");
        $this->assertFalse($paciente->Enfermedades->ECOG == 1);
        $this->assertFalse($paciente->Enfermedades->T == 1);
        $this->assertFalse($paciente->Enfermedades->T_tamano == 2.2);
        $this->assertFalse($paciente->Enfermedades->N == 1);
        $this->assertFalse($paciente->Enfermedades->N_afectacion == "Multiestacion");
        $this->assertFalse($paciente->Enfermedades->M == "0");
        $this->assertFalse($paciente->Enfermedades->num_afec_metas == "0");
        $this->assertFalse($paciente->Enfermedades->TNM == "IA1");
        $this->assertFalse($paciente->Enfermedades->tipo_muestra == "Citología");
        $this->assertFalse($paciente->Enfermedades->histologia_tipo == "Adenocarcinoma");
        $this->assertFalse($paciente->Enfermedades->histologia_subtipo == "Acinar");
        $this->assertFalse($paciente->Enfermedades->histologia_grado == "Mal diferenciado");
        $this->assertFalse($paciente->Enfermedades->tratamiento_dirigido == 0);
    }    

    /** @test */
    //Caso de prueba 7
    public function modificarEnfermedadConTamanoTVacioTest()
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
        //Realizamos la solicitud put con los datos de la enfermedad definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/datosgenerales', $enfermedad);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/datosgenerales');
        //Comprobamos que devuelve error en el campo fecha tamaño de T 
        $response->assertSessionHasErrors('T_tamano');
        //Comprobamos que en la vista enfermedad no se vean los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('datosenfermedad', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        //Comprobamos que los datos de la enfermedad no se hayan actualizado
        $this->assertFalse($paciente->Enfermedades->fecha_primera_consulta == "1999-05-05");
        $this->assertFalse($paciente->Enfermedades->fecha_diagnostico == "1999-06-06");
        $this->assertFalse($paciente->Enfermedades->ECOG == 1);
        $this->assertFalse($paciente->Enfermedades->T == 1);
        $this->assertFalse($paciente->Enfermedades->T_tamano == null);
        $this->assertFalse($paciente->Enfermedades->N == 1);
        $this->assertFalse($paciente->Enfermedades->N_afectacion == "Multiestacion");
        $this->assertFalse($paciente->Enfermedades->M == "0");
        $this->assertFalse($paciente->Enfermedades->num_afec_metas == "0");
        $this->assertFalse($paciente->Enfermedades->TNM == "IA1");
        $this->assertFalse($paciente->Enfermedades->tipo_muestra == "Citología");
        $this->assertFalse($paciente->Enfermedades->histologia_tipo == "Adenocarcinoma");
        $this->assertFalse($paciente->Enfermedades->histologia_subtipo == "Acinar");
        $this->assertFalse($paciente->Enfermedades->histologia_grado == "Mal diferenciado");
        $this->assertFalse($paciente->Enfermedades->tratamiento_dirigido == 0);
    }     

    /** @test */
    //Caso de prueba 8
    public function modificarEnfermedadConTamanoTNegativoTest()
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
        //Realizamos la solicitud put con los datos de la enfermedad definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/datosgenerales', $enfermedad);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/datosgenerales');
        //Comprobamos que devuelve error en el campo fecha tamaño de T 
        $response->assertSessionHasErrors('T_tamano');
        //Comprobamos que en la vista enfermedad no se vean los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('datosenfermedad', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        $view->assertDontSee('1999-06-06');
        $view->assertDontSee('-1.0');
        //Comprobamos que los datos de la enfermedad no se hayan actualizado
        $this->assertFalse($paciente->Enfermedades->fecha_primera_consulta == "1999-05-05");
        $this->assertFalse($paciente->Enfermedades->fecha_diagnostico == "1999-06-06");
        $this->assertFalse($paciente->Enfermedades->ECOG == 1);
        $this->assertFalse($paciente->Enfermedades->T == 1);
        $this->assertFalse($paciente->Enfermedades->T_tamano == -1.0);
        $this->assertFalse($paciente->Enfermedades->N == 1);
        $this->assertFalse($paciente->Enfermedades->N_afectacion == "Multiestacion");
        $this->assertFalse($paciente->Enfermedades->M == "0");
        $this->assertFalse($paciente->Enfermedades->num_afec_metas == "0");
        $this->assertFalse($paciente->Enfermedades->TNM == "IA1");
        $this->assertFalse($paciente->Enfermedades->tipo_muestra == "Citología");
        $this->assertFalse($paciente->Enfermedades->histologia_tipo == "Adenocarcinoma");
        $this->assertFalse($paciente->Enfermedades->histologia_subtipo == "Acinar");
        $this->assertFalse($paciente->Enfermedades->histologia_grado == "Mal diferenciado");
        $this->assertFalse($paciente->Enfermedades->tratamiento_dirigido == 0);
    }             
}
