<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades; 
use Tests\Ini\CrearDatosTest;

class CrearBiomarcadorTest extends TestCase
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
    public function crearBiomarcadorCorrectoTest()
    {
        //Accedemos la vista biomarcadores
        $response = $this->get('/paciente/999/enfermedad/biomarcadores')->assertSee('Biomarcadores');
        $biomarcador = [
            "ALK" => "on",
            "ALK_tipo" => "Traslocado",
            "ALK_subtipo" => "Gusión EML4",
        ];
        //Realizamos la solicitud post con los datos del biomarcador definidos anteriormente
        $response = $this->post('/paciente/999/enfermedad/biomarcadores', $biomarcador);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/biomarcadores');
        //Comprobamos que los datos del biomarcador se han introducido correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Enfermedades->Biomarcadores[0]->nombre == "ALK");
        $this->assertTrue($paciente->Enfermedades->Biomarcadores[0]->tipo == "Traslocado");
        $this->assertTrue($paciente->Enfermedades->Biomarcadores[0]->subtipo == "Gusión EML4");
    }
}
