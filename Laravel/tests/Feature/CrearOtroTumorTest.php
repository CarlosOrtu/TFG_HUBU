<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades; 
use Tests\Ini\CrearDatosTest;

class CrearOtroTumorTest extends TestCase
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
    public function crearOtroTumorCorrectoTest()
    {
        //Accedemos la vista otro tumor
        $response = $this->get('/paciente/999/enfermedad/otrostumores')->assertSee('Otros tumores');
        $otroTumor = [
            "tipo"=> "Mama",
        ];
        //Realizamos la solicitud post con los datos del otro tumor definido anteriormente
        $response = $this->post('/paciente/999/enfermedad/otrostumores', $otroTumor);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/otrostumores');
        //Comprobamos que los datos de la prueba realizada se han introducido correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Enfermedades->Otros_tumores[0]->tipo == "Mama");
    }
}
