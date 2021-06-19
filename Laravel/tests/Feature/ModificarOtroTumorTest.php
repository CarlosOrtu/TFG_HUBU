<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades; 
use App\Models\Otros_tumores;
use Tests\Ini\CrearDatosTest;

class ModificarOtroTumorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos el tumor a modificar
        $tumorAModificar = new Otros_tumores();
        $tumorAModificar->id_tumor = 999;
        $tumorAModificar->id_enfermedad = 999;
        $tumorAModificar->tipo = "Mama";
        $tumorAModificar->save();
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
    public function modificarOtroTumorCorrectoTest()
    {
        //Accedemos la vista pruebas
        $response = $this->get('/paciente/999/enfermedad/otrostumores')->assertSee('Otros tumores');
        $otroTumor = [
            "tipo"=> "Próstata",
        ];
        //Realizamos la solicitud put con los datos del otro tumor definido anteriormente
        $response = $this->put('/paciente/999/enfermedad/otrostumores/0', $otroTumor);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/otrostumores');
        //Comprobamos que los datos de la prueba realizada se han modificado correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Enfermedades->Otros_tumores[0]->tipo == "Próstata");
    }
}
