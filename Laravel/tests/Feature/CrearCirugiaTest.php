<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use Tests\Ini\CrearDatosTest;

class CrearCirugiaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario 
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
    public function crearCirugiaCorrectaTest()
    {
        //Accedemos la vista cirugía
        $response = $this->get('/paciente/999/tratamientos/cirugia')->assertSee('Cirugía');
        $cirugia = [
            "tipo" => "Lobectomía",
            "fecha" => "1999-05-05",
        ];
        //Realizamos la solicitud post con los datos de la cirugía definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/cirugia', $cirugia);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/cirugia');
        //Comprobamos que en la vista cirugía se ven los datos introducidos
        $paciente = Pacientes::find(999);
        $view = $this->view('cirugia', ['paciente' => $paciente]);
        $view->assertSee('1999-05-05');
        //Comprobamos que los datos de la cirugía se han introducido correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Tratamientos[0]->tipo == "Cirugia");
        $this->assertTrue($paciente->Tratamientos[0]->fecha_inicio == "1999-05-05");
        $this->assertTrue($paciente->Tratamientos[0]->subtipo == "Lobectomía");
    }

    /** @test */
    //Caso de prueba 2
    public function crearCirugiaFechaVacioTest()
    {
        //Accedemos la vista cirugía
        $response = $this->get('/paciente/999/tratamientos/cirugia')->assertSee('Cirugía');
        $cirugia = [
            "tipo" => "Lobectomía",
            "fecha" => "", 
        ];
        //Realizamos la solicitud post con los datos de la cirugía definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/cirugia', $cirugia);
        //Comprobamos si devuelve error en el campo fecha
        $response->assertSessionHasErrors('fecha');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/cirugia');
        //Comprobamos que los datos de la cirugía no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }

    /** @test */
    //Caso de prueba 3
    public function crearCirugiaFechaPosteriorActualTest()
    {
        //Accedemos la vista cirugía
        $response = $this->get('/paciente/999/tratamientos/cirugia')->assertSee('Cirugía');
        $cirugia = [
            "tipo" => "Lobectomía",
            "fecha" => "2022-05-05",
        ];
        //Realizamos la solicitud post con los datos de la cirugía definidos anteriormente
        $response = $this->post('/paciente/999/tratamientos/cirugia', $cirugia);
        //Comprobamos si devuelve error en el campo fecha
        $response->assertSessionHasErrors('fecha');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/tratamientos/cirugia');
        //Comprobamos que en la vista cirugía no ven los datos introducidos
        $paciente = Pacientes::find(999);
        $view = $this->view('cirugia', ['paciente' => $paciente]);
        $view->assertDontSee('2022-05-05');
        //Comprobamos que los datos de la cirugía no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Tratamientos) == 0);
    }
}
