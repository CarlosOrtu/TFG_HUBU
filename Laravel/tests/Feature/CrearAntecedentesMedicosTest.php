<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use Tests\Ini\CrearDatosTest;

class CrearAntecedentesMedicosTest extends TestCase
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
    public function crearAntecedenteMedicoCorrectoTest()
    {
        //Accedemos la vista antecedentes medicos
        $response = $this->get('/paciente/999/antecedentes/medicos')->assertSee('Antecedentes médicos');
        $antecedenteMedico = [
            "tipo"=> "EPOC",
        ];
        //Realizamos la solicitud post con los datos del antecedente medicos definidos anteriormente
        $response = $this->post('/paciente/999/antecedentes/medicos', $antecedenteMedico);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/antecedentes/medicos');
        //Comprobamos que los datos del antecedente medico se han introducido correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Antecedentes_medicos[0]->tipo_antecedente == "EPOC");
    }
}
