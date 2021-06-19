<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades; 
use App\Models\Pruebas_realizadas;
use Tests\Ini\CrearDatosTest;

class EliminarPruebaRealizadaTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos la prueba realizada a eliminar 
        $pruebaAEliminar = new Pruebas_realizadas();
        $pruebaAEliminar->id_prueba = 999;
        $pruebaAEliminar->id_enfermedad = 999;
        $pruebaAEliminar->tipo = "TAC";
        $pruebaAEliminar->save();
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
    public function eliminarPruebaRealizadaCorrectaTest()
    {
        //Accedemos la vista pruebas
        $response = $this->get('/paciente/999/enfermedad/pruebas')->assertSee('Pruebas realizadas');
        //Realizamos la solicitud delete
        $response = $this->delete('/paciente/999/enfermedad/pruebas/0');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/pruebas');
        //Comprobamos que los datos de la prueba realizada se han eliminado correctamente
        $prueba = Pruebas_realizadas::find(999);
        $this->assertTrue(empty($prueba));
    }
}
