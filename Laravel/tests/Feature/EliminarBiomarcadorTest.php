<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades; 
use App\Models\Biomarcadores; 
use Tests\Ini\CrearDatosTest;

class EliminarBiomarcadorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Crear el biomarcador a eliminar
        $biomarcadorAEliminar = new Biomarcadores();
        $biomarcadorAEliminar->id_biomarcador = 999;
        $biomarcadorAEliminar->id_enfermedad = 999;
        $biomarcadorAEliminar->nombre = "ALK";
        $biomarcadorAEliminar->tipo = "Traslocado";
        $biomarcadorAEliminar->subtipo = "Gusión EML4";
        $biomarcadorAEliminar->save();
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
    public function eliminarBiomarcadorCorrectoTest()
    {
        //Accedemos la vista biomarcadores
        $response = $this->get('/paciente/999/enfermedad/biomarcadores')->assertSee('Biomarcadores');
        //Realizamos la solicitud delete 
        $response = $this->delete('/paciente/999/enfermedad/biomarcadores/0');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/biomarcadores');
        //Comprobamos que el biomarcador se ha eliminado correctamente
        $biomarcador = Biomarcadores::find(999);
        $this->assertTrue(empty($biomarcador));
    }
}
