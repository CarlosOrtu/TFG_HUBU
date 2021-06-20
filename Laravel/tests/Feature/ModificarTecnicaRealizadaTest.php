<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades; 
use App\Models\Tecnicas_realizadas;
use Tests\Ini\CrearDatosTest;

class ModificarTecnicaRealizadaTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos la tecnica realizada a modificar
        $tecnicaAModificar = new Tecnicas_realizadas();
        $tecnicaAModificar->id_tecnica = 999;
        $tecnicaAModificar->id_enfermedad = 999;
        $tecnicaAModificar->tipo = "EBUS";
        $tecnicaAModificar->save();
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
    public function modificarTecnicaRealizadaCorrectaTest()
    {
        //Accedemos la vista pruebas
        $response = $this->get('/paciente/999/enfermedad/tecnicas')->assertSee('Técnicas');
        $prueba = [
            "tipo"=> "Broncoscopia",
        ];
        //Realizamos la solicitud put con los datos de la tecnica realizada definidos anteriormente
        $response = $this->put('/paciente/999/enfermedad/tecnicas/0', $prueba);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/tecnicas');
        //Comprobamos que los datos de la tecnica realizada se han modificado correctamente
        $paciente = Pacientes::find(999);
        $this->assertTrue($paciente->Enfermedades->Tecnicas_realizadas[0]->tipo == "Broncoscopia");
    }
}
