<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Sintomas;

class ModificarSintomaTest extends TestCase
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
        //Creamos el sintoma a modificar
        $sintomaAModificar = new Sintomas();
        $sintomaAModificar->id_enfermedad = 999;
        $sintomaAModificar->fecha_inicio = "1999-05-05";
        $sintomaAModificar->tipo = "Asintomático";
        $sintomaAModificar->save();

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
    public function modificarFechaSintomaCorrectoTest()
    {
        //Accedemos la vista sintomas
        $response = $this->get('/paciente/999/enfermedad/sintomas')->assertSee('Datos síntomas');
        $fecha = [
            "fecha_inicio"=> "1999-06-06",
        ];
        //Realizamos la solicitud put con la fecha definida anteriormente
        $response = $this->put('/paciente/999/enfermedad/sintomas/modificar/fecha', $fecha);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/sintomas');
        //Comprobamos que en la vista sintoma se vea los datos modificados correctamente
        $paciente = Pacientes::find(999);
        $view = $this->view('datossintomas', ['paciente' => $paciente]);
        $view->assertSee('1999-06-06');
        //Comprobamos que la fecha del sintoma se ha modificado correctamente
        $this->assertTrue($paciente->Enfermedades->Sintomas[0]->fecha_inicio == "1999-06-06");
    }

    /** @test */
    //Caso de prueba 2
    public function modificarTipoSintomaCorrectoTest()
    {
        //Accedemos la vista sintomas
        $response = $this->get('/paciente/999/enfermedad/sintomas')->assertSee('Datos síntomas');
        $tipo = [
            "tipo"=> "Tos",
        ];
        //Realizamos la solicitud put con el tipo definida anteriormente
        $response = $this->put('/paciente/999/enfermedad/sintomas/0', $tipo);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/sintomas');
        //Comprobamos que el tipo del sintoma se ha modificado correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Enfermedades->Sintomas[0]->tipo == "Tos");
    }

    /** @test */
    //Caso de prueba 3
    public function modificarFechaSintomaCampoFechaInicioVacioTest()
    {
        //Accedemos la vista sintomas
        $response = $this->get('/paciente/999/enfermedad/sintomas')->assertSee('Datos síntomas');
        $fecha = [
            "fecha_inicio"=> "",
        ];
        //Realizamos la solicitud put con la fecha definida anteriormente
        $response = $this->put('/paciente/999/enfermedad/sintomas/modificar/fecha', $fecha);
        //Comprobamos que devuelve error en el campo fecha inicio 
        $response->assertSessionHasErrors('fecha_inicio');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/sintomas');
        //Comprobamos que la fecha del sintoma no se ha modificado
        $paciente = Pacientes::find(999);
        $this->assertFalse($paciente->Enfermedades->Sintomas[0]->fecha_inicio == "");
    }

    /** @test */
    //Caso de prueba 4
    public function modificarFechaSintomaCampoFechaInicioPosteriorTest()
    {
        //Accedemos la vista sintomas
        $response = $this->get('/paciente/999/enfermedad/sintomas')->assertSee('Datos síntomas');
        $fecha = [
            "fecha_inicio"=> "2022-05-05",
        ];
        //Realizamos la solicitud put con la fecha definida anteriormente
        $response = $this->put('/paciente/999/enfermedad/sintomas/modificar/fecha', $fecha);
        //Comprobamos que devuelve error en el campo fecha inicio 
        $response->assertSessionHasErrors('fecha_inicio');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/sintomas');
        //Comprobamos que en la vista sintoma no se vean los datos modificados
        $paciente = Pacientes::find(999);
        $view = $this->view('datossintomas', ['paciente' => $paciente]);
        $view->assertDontSee('2022-05-05');
        //Comprobamos que la fecha del sintoma no se ha modificado
        $this->assertFalse($paciente->Enfermedades->Sintomas[0]->fecha_inicio == "2022-05-05");
    }
}
