<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use Tests\Ini\CrearDatosTest;

class CrearSeguimientoTest extends TestCase
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
        $usuario = Pacientes::find(999)->delete();
        parent::tearDown();
    }

    /** @test */
    //Caso de prueba 1
    public function crearSeguimientoCorrectoTest()
    {
        //Accedemos la vista seguimiento
        $response = $this->get('/paciente/999/seguimientos/nuevo')->assertSee('Nuevo seguimiento');
        $seguimiento = [
            "fecha" => "1999-05-05",
            "estado" => "Vivo sin enfermedad",
        ];
        //Realizamos la solicitud post con los datos del seguimiento definidos anteriormente
        $response = $this->post('/paciente/999/seguimientos/nuevo', $seguimiento);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/seguimientos/nuevo');
        //Comprobamos que en la vista seguimientos se ven los datos introducidos
        $paciente = Pacientes::find(999);
        $seguimientos = $paciente->Seguimientos;
        $seguimiento = $seguimientos[0];
        $view = $this->view('seguimientos',['paciente' => $paciente, 'seguimiento' => $seguimiento, 'posicion' => 0]);
        $view->assertSee('1999-05-05');
        //Comprobamos que los datos del seguimiento se han introducido correctamente
        $this->assertTrue($paciente->Seguimientos[0]->fecha == "1999-05-05");
        $this->assertTrue($paciente->Seguimientos[0]->estado == "Vivo sin enfermedad");
    }

    /** @test */
    //Caso de prueba 2
    public function crearSeguimientoFechaVacioTest()
    {
        //Accedemos la vista seguimiento
        $response = $this->get('/paciente/999/seguimientos/nuevo')->assertSee('Nuevo seguimiento');
        $seguimiento = [
            "fecha" => "",
            "estado" => "Vivo sin enfermedad",
        ];
        //Realizamos la solicitud post con los datos del seguimiento definidos anteriormente
        $response = $this->post('/paciente/999/seguimientos/nuevo', $seguimiento);
        //Comprobamos si devuelve error en el campo fecha
        $response->assertSessionHasErrors('fecha');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/seguimientos/nuevo');
        //Comprobamos que los datos del seguimiento no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Seguimientos) == 0);
    }

    /** @test */
    //Caso de prueba 3
    public function crearSeguimientoFechaPosteriorActualTest()
    {
        //Accedemos la vista seguimiento
        $response = $this->get('/paciente/999/seguimientos/nuevo')->assertSee('Nuevo seguimiento');
        $seguimiento = [
            "fecha" => "2022-05-05",
            "estado" => "Vivo sin enfermedad",
        ];
        //Realizamos la solicitud post con los datos del seguimiento definidos anteriormente
        $response = $this->post('/paciente/999/seguimientos/nuevo', $seguimiento);
        //Comprobamos si devuelve error en el campo fecha
        $response->assertSessionHasErrors('fecha');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/seguimientos/nuevo');
        //Comprobamos que los datos del seguimiento no se han introducido 
        $paciente = Pacientes::find(999);
        $this->assertTrue(count($paciente->Seguimientos) == 0);
    }
}
