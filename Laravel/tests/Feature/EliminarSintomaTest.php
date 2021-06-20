<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Sintomas;
use Tests\Ini\CrearDatosTest;

class EliminarSintomaTest extends TestCase
{
   protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos el sintoma a eliminar
        $sintomaAEliminar = new Sintomas();
        $sintomaAEliminar->id_sintoma = 999;
        $sintomaAEliminar->id_enfermedad = 999;
        $sintomaAEliminar->fecha_inicio = "1999-05-05";
        $sintomaAEliminar->tipo = "Asintomático";
        $sintomaAEliminar->save();

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
    public function eliminarSintomaCorrectoTest()
    {
        //Accedemos la vista sintomas
        $response = $this->get('/paciente/999/enfermedad/sintomas')->assertSee('Datos síntomas');
        //Realizamos la solicitud delete 
        $response = $this->delete('/paciente/999/enfermedad/sintomas/0');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/enfermedad/sintomas');
        //Comprobamos que en la vista sintoma se vea los datos modificados correctamente
        $paciente = Pacientes::find(999);
        $view = $this->view('datossintomas', ['paciente' => $paciente]);
        $view->assertDontSee('1999-05-05');
        //Comprobamos que el sintoma no esta en la base de datos
        $sintoma = Sintomas::find(999);
        $this->assertTrue(empty($sintoma));
    }
}
