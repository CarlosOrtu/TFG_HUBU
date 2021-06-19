<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades; 
use App\Models\Tecnicas_realizadas;
use Tests\Ini\CrearDatosTest;

class EliminarTecnicaRealizadaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos la tecnica realizada a eliminar
        $tecnicaAEliminar = new Tecnicas_realizadas();
        $tecnicaAEliminar->id_tecnica = 999;
        $tecnicaAEliminar->id_enfermedad = 999;
        $tecnicaAEliminar->tipo = "EBUS";
        $tecnicaAEliminar->save();
        //Realizamos el login con el administrador para poder acceder a todos las rutas de la web
        $response = $this->get('/login')->assertSee('Login');
        $credentials = [
            "email" => "administrador@gmail.com",
            "password" => "1234",
        ];
        $response = $this->post('/login', $credentials);
    }

    protected function tearDown(): void
    {
        //Eliminamos el usuario
        Pacientes::find(999)->delete();
        parent::tearDown();
    }

    /** @test */
    //Caso de prueba 1
    public function eliminarTecnicaRealizadaCorrectaTest()
    {
        //Accedemos la vista pruebas
        $response = $this->get('/paciente/999/enfermedad/tecnicas')->assertSee('Técnicas');
        //Realizamos la solicitud delete
        $response = $this->delete('/paciente/999/enfermedad/tecnicas/0');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/tecnicas');
        //Comprobamos que los datos de la tecnica realizada se han eliminado correctamente
        $tecnica = Tecnicas_realizadas::find(999);
        $this->assertTrue(empty($tecnica));
    }
}
