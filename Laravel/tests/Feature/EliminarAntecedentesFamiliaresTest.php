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

class EliminarAntecedentesFamiliaresTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //crear el antecedente familiar a eliminar
        $antFamEliminar = new Antecedentes_familiares();
        $antFamEliminar->id_paciente = 999;
        $antFamEliminar->id_antecedente_f = 999;
        $antFamEliminar->familiar = "Madre";
        $antFamEliminar->save();
        //Crear la enfermedad del familiar a eliminar
        $enfFamEliminar = new Enfermedades_familiar();
        $enfFamEliminar->id_antecedente_f = 999;
        $enfFamEliminar->id_enfermedad_f = 999;
        $enfFamEliminar->tipo = "Pulmón";
        $enfFamEliminar->save();
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
    public function eliminarAntecedenteFamiliarCorrectoTest()
    {
        //Accedemos la vista antecedentes familiares
        $response = $this->get('/paciente/999/antecedentes/familiares')->assertSee('Antecedentes familiares');
        //Realizamos la solicitud delete
        $response = $this->delete('/paciente/999/antecedentes/familiares/0');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/antecedentes/familiares');
        //Comprobamos que los datos del antecedente familiar se han eliminado correctamente
        $antecedenteFamiliar = Antecedentes_familiares::find(999);
        $enfermedadFamiliar = Enfermedades_familiar::find(999);
        $this->assertTrue(empty($antecedenteFamiliar));
        $this->assertTrue(empty($enfermedadFamiliar));
    }
}
