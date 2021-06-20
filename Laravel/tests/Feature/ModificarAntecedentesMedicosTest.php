<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Antecedentes_medicos;
use Tests\Ini\CrearDatosTest;

class ModificarAntecedentesMedicosTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos el antecedente médico a modificar
        $antMModificar = new Antecedentes_medicos();
        $antMModificar->id_paciente = 999;
        $antMModificar->id_antecedente_m = 999;
        $antMModificar->tipo_antecedente = "EPOC";
        $antMModificar->save();
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
    public function modificarAntecedenteMedicoCorrectoTest()
    {
        //Accedemos la vista antecedentes medicos
        $response = $this->get('/paciente/999/antecedentes/medicos')->assertSee('Antecedentes médicos');
        $antecedenteMedico = [
            "tipo"=> "Ictus",
        ];
        //Realizamos la solicitud put con los datos del antecedente medicos definidos anteriormente
        $response = $this->put('/paciente/999/antecedentes/medicos/0', $antecedenteMedico);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/antecedentes/medicos');
        //Comprobamos que los datos del antecedente medico se han modificado correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Antecedentes_medicos[0]->tipo_antecedente == "Ictus");
    }
}
