<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Metastasis;
use Tests\Ini\CrearDatosTest;

class ModificarMetastasisTest extends TestCase
{
  protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Crear metastasis a modificar
        $metastasisModificar = new Metastasis();
        $metastasisModificar->id_metastasis = 999;
        $metastasisModificar->id_enfermedad = 999;
        $metastasisModificar->tipo = "Hígado";
        $metastasisModificar->save();

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
    public function modificarMetastasisCorrectaTest()
    {
        //Accedemos la vista metastasis
        $response = $this->get('/paciente/999/enfermedad/metastasis')->assertSee('Metástasis');
        $metastasis = [
            "localizacion"=> "Hueso",
        ];
        //Realizamos la solicitud put con los datos de la metastasis definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/metastasis/0', $metastasis);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/metastasis');
        //Comprobamos que los datos de la metastasis se han modificado correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Enfermedades->Metastasis[0]->tipo == "Hueso");
    }
}
