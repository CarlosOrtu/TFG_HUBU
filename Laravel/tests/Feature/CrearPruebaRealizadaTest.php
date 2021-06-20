<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades; 
use Tests\Ini\CrearDatosTest;
 
class CrearPruebaRealizadaTest extends TestCase
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
    public function crearPruebaRealizadaCorrectaTest()
    {
        //Accedemos la vista pruebas
        $response = $this->get('/paciente/999/enfermedad/pruebas')->assertSee('Pruebas realizadas');
        $prueba = [
            "tipo"=> "TAC",
        ];
        //Realizamos la solicitud post con los datos de la prueba realizada definidos anteriormente
        $response = $this->post('/paciente/999/enfermedad/pruebas', $prueba);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/pruebas');
        //Comprobamos que los datos de la prueba realizada se han introducido correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Enfermedades->Pruebas_realizadas[0]->tipo == "TAC");
    }
}
