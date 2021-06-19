<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use Tests\Ini\CrearDatosTest;

class CrearAntecedentesFamiliaresTest extends TestCase
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
    public function crearAntecedenteFamiliarCorrectoTest()
    {
        //Accedemos la vista antecedentes familiares
        $response = $this->get('/paciente/999/antecedentes/familiares')->assertSee('Antecedentes familiares');
        $antecedenteFamiliar = [
            "familiar" => "Madre",
            "enfermedades" => ["Pulmón"],
        ];
        //Realizamos la solicitud post con los datos del antecedente familiares definidos anteriormente
        $response = $this->post('/paciente/999/antecedentes/familiares', $antecedenteFamiliar);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/antecedentes/familiares');
        //Comprobamos que los datos del antecedente familiar se han introducido correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Antecedentes_familiares[0]->familiar == "Madre");
        $this->assertTrue($paciente->Antecedentes_familiares[0]->Enfermedades_familiar[0]->tipo == "Pulmón");
    }

    /** @test */
    //Caso de prueba 2
    public function crearAntecedenteFamiliarCampoFamiliarVacioTest()
    {
        //Accedemos la vista antecedentes familiares
        $response = $this->get('/paciente/999/antecedentes/familiares')->assertSee('Antecedentes familiares');
        $antecedenteFamiliar = [
            "familiar" => "",
            "enfermedades" => ["Pulmón"],
        ];
        //Realizamos la solicitud post con los datos del antecedente familiares definidos anteriormente
        $response = $this->post('/paciente/999/antecedentes/familiares', $antecedenteFamiliar);
        //Comprobamos que devuelve error en el campo familiar 
        $response->assertSessionHasErrors('familiar');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/antecedentes/familiares');
        //Comprobamos que los datos del antecedente familiar no se han introducido correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue(empty($paciente->Antecedentes_familiares[0]));
    }
}
