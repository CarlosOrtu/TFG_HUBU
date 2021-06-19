<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Seguimientos;
use Tests\Ini\CrearDatosTest;

class EliminarSeguimientoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos el seguimiento a eliminar
        $seguimientoAEliminar = new Seguimientos();
        $seguimientoAEliminar->id_paciente = 999;
        $seguimientoAEliminar->id_seguimiento = 999;
        $seguimientoAEliminar->fecha = "1998-05-05";
        $seguimientoAEliminar->estado = "1998-05-05";
        $seguimientoAEliminar->save();
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
    public function eliminarSeguimientoCorrectoTest()
    {
        //Accedemos la vista seguimiento
        $response = $this->get('/paciente/999/seguimientos/modificar/0')->assertSee('Seguimiento 1');
        //Realizamos la solicitud delete
        $response = $this->delete('/paciente/999/seguimientos/modificar/0');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/seguimientos/nuevo');
        //Comprobamos que los datos del seguimiento se han eliminado correctamente
        $seguimientoEliminado = Seguimientos::find(999);
        $this->assertTrue(empty($seguimientoEliminado));
    }
}
