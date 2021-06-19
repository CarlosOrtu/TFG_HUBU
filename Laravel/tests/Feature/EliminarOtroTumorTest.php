<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades; 
use App\Models\Otros_tumores;
use Tests\Ini\CrearDatosTest;

class EliminarOtroTumorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos el tumor a eliminar
        $tumorAEliminar = new Otros_tumores();
        $tumorAEliminar->id_tumor = 999;
        $tumorAEliminar->id_enfermedad = 999;
        $tumorAEliminar->tipo = "Mama";
        $tumorAEliminar->save();
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
    public function eliminarOtroTumorCorrectoTest()
    {
        //Accedemos la vista pruebas
        $response = $this->get('/paciente/999/enfermedad/otrostumores')->assertSee('Otros tumores');
        //Realizamos la solicitud delete
        $response = $this->delete('/paciente/999/enfermedad/otrostumores/0');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/otrostumores');
        //Comprobamos que los datos de la prueba realizada se han eliminado correctamente
        $tumor = Otros_tumores::find(999);
        $this->assertTrue(empty($tumor));
    }
}
