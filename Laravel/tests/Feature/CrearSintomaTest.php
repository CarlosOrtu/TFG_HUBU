<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use Tests\Ini\CrearDatosTest;

class CrearSintomaTest extends TestCase
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
