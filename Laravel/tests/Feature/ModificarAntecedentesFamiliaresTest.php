<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Antecedentes_familiares;
use App\Models\Enfermedades_familiar;
use Tests\Ini\CrearDatosTest;

class ModificarAntecedentesFamiliaresTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //crear el antecedente familiar a modificar
        $antecedenteFamiliarAModificar = new Antecedentes_familiares();
        $antecedenteFamiliarAModificar->id_paciente = 999;
        $antecedenteFamiliarAModificar->id_antecedente_f = 999;
        $antecedenteFamiliarAModificar->familiar = "Madre";
        $antecedenteFamiliarAModificar->save();
        //Crear la enfermedad del familiar a modificar
        $enfermedadFamiliarAModificar = new Enfermedades_familiar();
        $enfermedadFamiliarAModificar->id_antecedente_f = 999;
        $enfermedadFamiliarAModificar->id_enfermedad_f = 999;
        $enfermedadFamiliarAModificar->tipo = "Pulmón";
        $enfermedadFamiliarAModificar->save();
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
    public function modificarAntecedenteFamiliarCorrectoTest()
    {
        //Accedemos la vista antecedentes familiares
        $response = $this->get('/paciente/999/antecedentes/familiares')->assertSee('Antecedentes familiares');
        $antecedenteFamiliar = [
            "familiar" => "Padre",
            "enfermedades" => ["Vejiga"],
        ];
        //Realizamos la solicitud put con los datos del antecedente familiares definidos anteriormente
        $response = $this->put('/paciente/999/antecedentes/familiares/0', $antecedenteFamiliar);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/antecedentes/familiares');
        //Comprobamos que los datos del antecedente familiar se han modificado correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Antecedentes_familiares[0]->familiar == "Padre");
        $this->assertTrue($paciente->Antecedentes_familiares[0]->Enfermedades_familiar[0]->tipo == "Vejiga");
    }

    /** @test */
    //Caso de prueba 2
    public function modificarAntecedenteFamiliarCampoFamiliarVacioTest()
    {
        //Accedemos la vista antecedentes familiares
        $response = $this->get('/paciente/999/antecedentes/familiares')->assertSee('Antecedentes familiares');
        $antecedenteFamiliar = [
            "familiar" => "",
            "enfermedades" => ["Vejiga"],
        ];
        //Realizamos la solicitud put con los datos del antecedente familiares definidos anteriormente
        $response = $this->put('/paciente/999/antecedentes/familiares/0', $antecedenteFamiliar);
        //Comprobamos que devuelve error en el campo familiar 
        $response->assertSessionHasErrors('familiar');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/antecedentes/familiares');
        //Comprobamos que los datos del antecedente familiar no se han modificado correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Antecedentes_familiares[0]->familiar == "Madre");
        $this->assertTrue($paciente->Antecedentes_familiares[0]->Enfermedades_familiar[0]->tipo == "Pulmón");
    }
}
