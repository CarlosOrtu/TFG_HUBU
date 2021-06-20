<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Seguimientos;
use Tests\Ini\CrearDatosTest;

class ModificarSeguimientoTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        //Creamos el usuario y la enfermedad
        $iniDatos = new CrearDatosTest;
        $iniDatos->crearPaciente();
        $iniDatos->crearEnfermedad();
        //Creamos el seguimiento a modificar
        $segAModificar = new Seguimientos();
        $segAModificar->id_paciente = 999;
        $segAModificar->id_seguimiento = 999;
        $segAModificar->fecha = "1998-05-05";
        $segAModificar->estado = "1998-05-05";
        $segAModificar->save();
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
    public function modificarSeguimientoCorrectoTest()
    {
        //Accedemos la vista seguimiento
        $response = $this->get('/paciente/999/seguimientos/modificar/0')->assertSee('Seguimiento 1');
        $seguimiento = [
            "fecha" => "1999-05-05",
            "estado" => "Vivo sin enfermedad",
        ];
        //Realizamos la solicitud put con los datos del seguimiento definidos anteriormente
        $response = $this->put('/paciente/999/seguimientos/modificar/0', $seguimiento);
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/seguimientos/modificar/0');
        //Comprobamos que en la vista seguimientos se ven los datos modificados
        $paciente = Pacientes::find(999);
        $seguimientos = $paciente->Seguimientos;
        $seguimiento = $seguimientos[0];
        $view = $this->view('seguimientos',['paciente' => $paciente, 'seguimiento' => $seguimiento, 'posicion' => 0]);
        $view->assertSee('1999-05-05');
        //Comprobamos que los datos del seguimiento se han modificado correctamente
        $this->assertTrue($paciente->Seguimientos[0]->fecha == "1999-05-05");
        $this->assertTrue($paciente->Seguimientos[0]->estado == "Vivo sin enfermedad");
    }

    /** @test */
    //Caso de prueba 2
    public function modificarSeguimientoFechaVacioTest()
    {
        //Accedemos la vista seguimiento
        $response = $this->get('/paciente/999/seguimientos/modificar/0')->assertSee('Seguimiento 1');
        $seguimiento = [
            "fecha" => "",
            "estado" => "Vivo sin enfermedad",
        ];
        //Realizamos la solicitud put con los datos del seguimiento definidos anteriormente
        $response = $this->put('/paciente/999/seguimientos/modificar/0', $seguimiento);
        //Comprobamos si devuelve error en el campo fecha
        $response->assertSessionHasErrors('fecha');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/seguimientos/modificar/0');
        //Comprobamos que los datos del seguimiento no se han modificado 
        $paciente = Pacientes::find(999);
        $this->assertFalse($paciente->Seguimientos[0]->fecha == "");
        $this->assertFalse($paciente->Seguimientos[0]->estado == "Vivo sin enfermedad");
    }

    /** @test */
    //Caso de prueba 3
    public function modificarSeguimientoFechaPosteriorActualTest()
    {
        //Accedemos la vista seguimiento
        $response = $this->get('/paciente/999/seguimientos/modificar/0')->assertSee('Seguimiento 1');
        $seguimiento = [
            "fecha" => "2022-05-05",
            "estado" => "Vivo sin enfermedad",
        ];
        //Realizamos la solicitud put con los datos del seguimiento definidos anteriormente
        $response = $this->put('/paciente/999/seguimientos/modificar/0', $seguimiento);
        //Comprobamos si devuelve error en el campo fecha
        $response->assertSessionHasErrors('fecha');
        //Comprobamos si se redirige correctamente
        $response->assertRedirect('/paciente/999/seguimientos/modificar/0');
        //Comprobamos que en la vista seguimientos se ven los datos modificados
        $paciente = Pacientes::find(999);
        $seguimientos = $paciente->Seguimientos;
        $seguimiento = $seguimientos[0];
        $view = $this->view('seguimientos',['paciente' => $paciente, 'seguimiento' => $seguimiento, 'posicion' => 0]);
        $view->assertDontSee('2022-05-05');
        //Comprobamos que los datos del seguimiento no se han modificado 
        $this->assertFalse($paciente->Seguimientos[0]->fecha == "2022-05-05");
        $this->assertFalse($paciente->Seguimientos[0]->estado == "Vivo sin enfermedad");
    }
}
