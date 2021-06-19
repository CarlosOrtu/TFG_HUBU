<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Antecedentes_medicos;
use Tests\Ini\CrearDatosTest;

class EliminarAntecedentesMedicosTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos el antecedente médico a eliminar
        $antMEliminar = new Antecedentes_medicos();
        $antMEliminar->id_paciente = 999;
        $antMEliminar->id_antecedente_m = 999;
        $antMEliminar->tipo_antecedente = "EPOC";
        $antMEliminar->save();
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
    public function eliminarAntecedenteMedicoCorrectoTest()
    {
        //Accedemos la vista antecedentes medicos
        $response = $this->get('/paciente/999/antecedentes/medicos')->assertSee('Antecedentes médicos');
        //Realizamos la solicitud delete
        $response = $this->delete('/paciente/999/antecedentes/medicos/0');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/antecedentes/medicos');
        //Comprobamos que los datos del antecedente medico se han eliminado correctamente
        $antecedenteM = Antecedentes_medicos::find(999);
        $this->assertTrue(empty($antecedenteM));
    }
}
