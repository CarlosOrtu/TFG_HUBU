<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedad;
use App\Models\Seguimientos;

class ModificarSeguimientoTest extends TestCase
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
        //Creamos el seguimiento a modificar
        $seguimientoAModificar = new Seguimientos();
        $seguimientoAModificar->id_paciente = 999;
        $seguimientoAModificar->id_seguimiento = 999;
        $seguimientoAModificar->fecha = "1998-05-05";
        $seguimientoAModificar->estado = "1998-05-05";
        $seguimientoAModificar->save();
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
    public function modificarSeguimientoCorrectoTest()
    {
        //Accedemos la vista seguimiento
        $response = $this->get('/paciente/999/seguimientos/modificar/0')->assertSee('Seguimiento 1');
        $seguimiento = [
            "fecha" => "1999-05-05",
            "estado" => "Vivo sin enfermedad",
        ];
        //Realizamos la solicitud put con los datos del seguimiento definidos anteriormente
        $response = $this->put('/paciente/999/seguimientos/modificar/0', $seguimiento);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/seguimientos/modificar/0');
        //Comprobamos que en la vista seguimientos se ven los datos modificados
        $paciente = Pacientes::find(999);
        $seguimientos = $paciente->Seguimientos;
        $seguimiento = $seguimientos[0];
        $view = $this->view('seguimientos',['paciente' => $paciente, 'seguimiento' => $seguimiento, 'posicion' => 0]);
        $view->assertSee('1999-05-05');
        //Comprobamos que los datos del seguimiento se han modificado correctamente
        $this->assertTrue($paciente->Seguimientos[0]->fecha == "1999-05-05");
        $this->assertTrue($paciente->Seguimientos[0]->estado == "Vivo sin enfermedad");
    }

    /** @test */
    //Caso de prueba 2
    public function modificarSeguimientoFechaVacioTest()
    {
        //Accedemos la vista seguimiento
        $response = $this->get('/paciente/999/seguimientos/modificar/0')->assertSee('Seguimiento 1');
        $seguimiento = [
            "fecha" => "",
            "estado" => "Vivo sin enfermedad",
        ];
        //Realizamos la solicitud put con los datos del seguimiento definidos anteriormente
        $response = $this->put('/paciente/999/seguimientos/modificar/0', $seguimiento);
        //Comprobamos si devuelve error en el campo fecha
        $response->assertSessionHasErrors('fecha');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/seguimientos/modificar/0');
        //Comprobamos que los datos del seguimiento no se han modificado 
        $paciente = Pacientes::find(999);
        $this->assertFalse($paciente->Seguimientos[0]->fecha == "");
        $this->assertFalse($paciente->Seguimientos[0]->estado == "Vivo sin enfermedad");
    }

    /** @test */
    //Caso de prueba 3
    public function modificarSeguimientoFechaPosteriorActualTest()
    {
        //Accedemos la vista seguimiento
        $response = $this->get('/paciente/999/seguimientos/modificar/0')->assertSee('Seguimiento 1');
        $seguimiento = [
            "fecha" => "2022-05-05",
            "estado" => "Vivo sin enfermedad",
        ];
        //Realizamos la solicitud put con los datos del seguimiento definidos anteriormente
        $response = $this->put('/paciente/999/seguimientos/modificar/0', $seguimiento);
        //Comprobamos si devuelve error en el campo fecha
        $response->assertSessionHasErrors('fecha');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/seguimientos/modificar/0');
        //Comprobamos que en la vista seguimientos se ven los datos modificados
        $paciente = Pacientes::find(999);
        $seguimientos = $paciente->Seguimientos;
        $seguimiento = $seguimientos[0];
        $view = $this->view('seguimientos',['paciente' => $paciente, 'seguimiento' => $seguimiento, 'posicion' => 0]);
        $view->assertDontSee('2022-05-05');
        //Comprobamos que los datos del seguimiento no se han modificado 
        $this->assertFalse($paciente->Seguimientos[0]->fecha == "2022-05-05");
        $this->assertFalse($paciente->Seguimientos[0]->estado == "Vivo sin enfermedad");
    }
}
