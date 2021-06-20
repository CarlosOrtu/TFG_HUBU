<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades; 
use App\Models\Pruebas_realizadas;
use Tests\Ini\CrearDatosTest;

class ModificarPruebaRealizadaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos la prueba realizada a modificar 
        $pruebaAModificar = new Pruebas_realizadas();
        $pruebaAModificar->id_prueba = 999;
        $pruebaAModificar->id_enfermedad = 999;
        $pruebaAModificar->tipo = "TAC";
        $pruebaAModificar->save();
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
    public function modificarPruebaRealizadaCorrectaTest()
    {
        //Accedemos la vista pruebas
        $response = $this->get('/paciente/999/enfermedad/pruebas')->assertSee('Pruebas realizadas');
        $prueba = [
            "tipo"=> "TAP",
        ];
        //Realizamos la solicitud put con los datos de la prueba realizada definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/pruebas/0', $prueba);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/pruebas');
        //Comprobamos que los datos de la prueba realizada se han modificado correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Enfermedades->Pruebas_realizadas[0]->tipo == "TAP");
    }
}
